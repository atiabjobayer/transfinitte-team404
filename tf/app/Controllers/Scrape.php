<?php

namespace App\Controllers;

use CodeIgniter\Database\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Scrape extends BaseController
{
	public function cliIndex($author, $repo){
		$this->getIndex($author, $repo);
	}

	public function getIndex($author, $repo)
	{
		$accessToken = env('GH_ACCESS_TOKEN');

		cache()->delete('scrape_repo_owner');
		cache()->delete('scrape_repo_name');

		cache()->save('scrape_repo_owner', $author, 3600);
		cache()->save('scrape_repo_name', $repo, 3600);

		$client = new Client();

		$jsonLines = [];

		// get project name, description, author

		$response = $client->request('GET', "https://api.github.com/repos/$author/$repo", [
			'headers' => [
				'Accept' => 'application/vnd.github+json',
				'Authorization'     => "Bearer $accessToken",
				'X-GitHub-Api-Version' => '2022-11-28',
			]
		]);

		if($response->getStatusCode() != 200){
			echo "Couldn't get repo details. Non 200 response";
			exit;
		}

		cache()->save('scrape_update_state', 'active', 300);

		$repoDetail = json_decode($response->getBody(), true);

		$infoText = "Project name: " . $repoDetail['name'];

		$jsonLines[] = "$infoText";

		$infoText = " Project summary: " . $repoDetail['description'];

		$jsonLines[] = "$infoText";

		$response = $client->request('GET', "https://api.github.com/repos/$author/$repo/contents/README.md", [
			'headers' => [
				'Accept' => 'application/vnd.github+json',
				'Authorization'     => "Bearer $accessToken",
				'X-GitHub-Api-Version' => '2022-11-28',
			]
		]);

		if($response->getStatusCode() != 200){
			echo "Couldn't get repo readme. Non 200 response";
			exit;
		}

		$description = base64_decode(json_decode($response->getBody(), true)['content']);

		$jsonLines[] = "Project Description: $description";

		// get issues

		try{
			$page = 0;

			$issueNumbers = [];

			while(true){
				$page++;
				$response = $client->request('GET', "https://api.github.com/repos/$author/$repo/issues?state=all&per_page=10&page=$page", [
					'headers' => [
						'Accept' => 'application/vnd.github+json',
						'Authorization'     => "Bearer $accessToken",
						'X-GitHub-Api-Version' => '2022-11-28',
					]
				]);

				if($response->getStatusCode() != 200){
					echo "Couldn't get issue. Non 200 response";
					exit;
				}

				cache()->save('scrape_update_state', 'active', 300);

				$issues = json_decode($response->getBody(), true);

				foreach ($issues as $issue){
					$issueNumbers[] = $issue['number'];
				}

//				var_dump($issues);

				if(sizeof($issues) < 1){
					break;
				}

//				break;
			}



//			var_dump($issueNumbers);

			foreach ($issueNumbers as $issueNumber){
				cache()->save('scrape_update_state', 'active', 300);
				$response = $client->request('GET', "https://api.github.com/repos/$author/$repo/issues/$issueNumber", [
					'headers' => [
						'Accept' => 'application/vnd.github+json',
						'Authorization'     => "Bearer $accessToken",
						'X-GitHub-Api-Version' => '2022-11-28',
					]
				]);

				if($response->getStatusCode() != 200){
					echo "Couldn't get issue details. Non 200 response";
					exit;
				}

				$issue = json_decode($response->getBody(), true);

//				var_dump($issue);

				$descText = "Issue number #$issueNumber. ";

				$descText .= "Issue title: " . $issue['title'];
				$descText .= " | Issue description: " . $issue['body'];

				foreach ($issue['labels'] as $label){
					$descText .= " This issue contains a tag named " . $label['name'] . ".";

					if(strlen($label['description']) > 0){
						$descText .= " This tag means " . $label['description'] . ".";
					}
				}

				if($issue['state'] == "open"){
					if(sizeof($issue['assignees']) > 0){
						$descText .= " This issue has not yet been assigned to anyone to resolve.";
					} else {
						$descText .= " Developers are working to fix this issue.";
					}
				} else{
					$descText .= " This issue has been closed which means this has been resolved.";
				}

				$jsonLines[] = $descText;

				$response = $client->request('GET', "https://api.github.com/repos/$author/$repo/issues/$issueNumber/comments", [
					'headers' => [
						'Accept' => 'application/vnd.github+json',
						'Authorization'     => "Bearer $accessToken",
						'X-GitHub-Api-Version' => '2022-11-28',
					]
				]);

				$issueComments = json_decode($response->getBody(), true);

//				var_dump($issueComments);

				if(sizeof($issueComments) < 1){
					$jsonLines[] = "There are no comments in the issue #{$issueNumber}.";
				}

				foreach ($issueComments as $issueComment){
					$jsonLines[] = "Comment on Issue #{$issueNumber} {$issue['title']} by {$issueComment['user']['login']} : {$issueComment['body']}";
				}
			}

			$fileContent = "";

			foreach($jsonLines as $line){
				$processed = str_replace(array("\r", "\n"), '', $line);
				$processed = str_replace("\"", '\'', $processed);
				echo "{\"doc\": \"$processed\"}" . "<br><br>";
				$fileContent .= "{\"doc\": \"$processed\"}" . "\n";
			}

			$filePath = env('LLM_DATA_DIR');

			file_put_contents($filePath, $fileContent);

			cache()->save('scrape_update_state', 'completed', 60);

		} catch (GuzzleException $e) {
			echo "An error occurred. " . $e->getMessage();
		}

		// get release and release notes
	}

	public function getInput($author, $repo){
		$data = [
			'author' => $author,
			'repo' => $repo,
		];

		return view('progress', $data);
	}

	public function getUpdateState(){
		if(is_null(cache('scrape_update_state'))){
			return "inactive";
		}

		return cache('scrape_update_state');
	}
}
