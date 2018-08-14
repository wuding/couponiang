<?php
namespace app\module\api\controller;

class Git extends _Abstract
{
	public $disableView = true; # 
	
	/**
	 * 钩子
	 *
	 */
	public function hooks()
	{
		header('Content-Type: application/json; charset=utf-8');
		$secret = 'bunny';
		$result = null;
		
		// 获取签名、事件类型、装载内容
		$signature = isset($_SERVER['HTTP_X_HUB_SIGNATURE']) ? $_SERVER['HTTP_X_HUB_SIGNATURE'] : '';
		$github_event = isset($_SERVER['HTTP_X_GITHUB_EVENT']) ? $_SERVER['HTTP_X_GITHUB_EVENT'] : '';
		$php_input = file_get_contents('php://input'); 
		# $php_input = 'payload=%7B%22ref%22%3A%22refs%2Fheads%2Fmaster%22%2C%22before%22%3A%228909e1e0182dd2563c024a699b4fca456c0f5195%22%2C%22after%22%3A%22009a12040858af96a17cb12695bb363d55891eda%22%2C%22created%22%3Afalse%2C%22deleted%22%3Afalse%2C%22forced%22%3Afalse%2C%22base_ref%22%3Anull%2C%22compare%22%3A%22https%3A%2F%2Fgithub.com%2Fwuding%2Fcouponiang%2Fcompare%2F8909e1e0182d...009a12040858%22%2C%22commits%22%3A%5B%7B%22id%22%3A%22009a12040858af96a17cb12695bb363d55891eda%22%2C%22tree_id%22%3A%22c1dcd34f4713f9e258ed3a5d061e8c7202b8e431%22%2C%22distinct%22%3Atrue%2C%22message%22%3A%22api+%E6%96%B0%E5%A2%9Egithub%E9%92%A9%E5%AD%90%22%2C%22timestamp%22%3A%222018-08-14T15%3A09%3A06%2B08%3A00%22%2C%22url%22%3A%22https%3A%2F%2Fgithub.com%2Fwuding%2Fcouponiang%2Fcommit%2F009a12040858af96a17cb12695bb363d55891eda%22%2C%22author%22%3A%7B%22name%22%3A%22wuding%22%2C%22email%22%3A%22netjoin%40gmail.com%22%2C%22username%22%3A%22wuding%22%7D%2C%22committer%22%3A%7B%22name%22%3A%22wuding%22%2C%22email%22%3A%22netjoin%40gmail.com%22%2C%22username%22%3A%22wuding%22%7D%2C%22added%22%3A%5B%22app%2Fmodule%2Fapi%2Fcontroller%2Fgit.php%22%5D%2C%22removed%22%3A%5B%5D%2C%22modified%22%3A%5B%22src%2FAstro%2FController.php%22%5D%7D%5D%2C%22head_commit%22%3A%7B%22id%22%3A%22009a12040858af96a17cb12695bb363d55891eda%22%2C%22tree_id%22%3A%22c1dcd34f4713f9e258ed3a5d061e8c7202b8e431%22%2C%22distinct%22%3Atrue%2C%22message%22%3A%22api+%E6%96%B0%E5%A2%9Egithub%E9%92%A9%E5%AD%90%22%2C%22timestamp%22%3A%222018-08-14T15%3A09%3A06%2B08%3A00%22%2C%22url%22%3A%22https%3A%2F%2Fgithub.com%2Fwuding%2Fcouponiang%2Fcommit%2F009a12040858af96a17cb12695bb363d55891eda%22%2C%22author%22%3A%7B%22name%22%3A%22wuding%22%2C%22email%22%3A%22netjoin%40gmail.com%22%2C%22username%22%3A%22wuding%22%7D%2C%22committer%22%3A%7B%22name%22%3A%22wuding%22%2C%22email%22%3A%22netjoin%40gmail.com%22%2C%22username%22%3A%22wuding%22%7D%2C%22added%22%3A%5B%22app%2Fmodule%2Fapi%2Fcontroller%2Fgit.php%22%5D%2C%22removed%22%3A%5B%5D%2C%22modified%22%3A%5B%22src%2FAstro%2FController.php%22%5D%7D%2C%22repository%22%3A%7B%22id%22%3A141953094%2C%22node_id%22%3A%22MDEwOlJlcG9zaXRvcnkxNDE5NTMwOTQ%3D%22%2C%22name%22%3A%22couponiang%22%2C%22full_name%22%3A%22wuding%2Fcouponiang%22%2C%22owner%22%3A%7B%22name%22%3A%22wuding%22%2C%22email%22%3A%22netjoin%40gmail.com%22%2C%22login%22%3A%22wuding%22%2C%22id%22%3A235209%2C%22node_id%22%3A%22MDQ6VXNlcjIzNTIwOQ%3D%3D%22%2C%22avatar_url%22%3A%22https%3A%2F%2Favatars3.githubusercontent.com%2Fu%2F235209%3Fv%3D4%22%2C%22gravatar_id%22%3A%22%22%2C%22url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%22%2C%22html_url%22%3A%22https%3A%2F%2Fgithub.com%2Fwuding%22%2C%22followers_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Ffollowers%22%2C%22following_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Ffollowing%7B%2Fother_user%7D%22%2C%22gists_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Fgists%7B%2Fgist_id%7D%22%2C%22starred_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Fstarred%7B%2Fowner%7D%7B%2Frepo%7D%22%2C%22subscriptions_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Fsubscriptions%22%2C%22organizations_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Forgs%22%2C%22repos_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Frepos%22%2C%22events_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Fevents%7B%2Fprivacy%7D%22%2C%22received_events_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Freceived_events%22%2C%22type%22%3A%22User%22%2C%22site_admin%22%3Afalse%7D%2C%22private%22%3Afalse%2C%22html_url%22%3A%22https%3A%2F%2Fgithub.com%2Fwuding%2Fcouponiang%22%2C%22description%22%3A%22A+comparison+shopping+website%22%2C%22fork%22%3Afalse%2C%22url%22%3A%22https%3A%2F%2Fgithub.com%2Fwuding%2Fcouponiang%22%2C%22forks_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fforks%22%2C%22keys_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fkeys%7B%2Fkey_id%7D%22%2C%22collaborators_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fcollaborators%7B%2Fcollaborator%7D%22%2C%22teams_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fteams%22%2C%22hooks_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fhooks%22%2C%22issue_events_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fissues%2Fevents%7B%2Fnumber%7D%22%2C%22events_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fevents%22%2C%22assignees_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fassignees%7B%2Fuser%7D%22%2C%22branches_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fbranches%7B%2Fbranch%7D%22%2C%22tags_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Ftags%22%2C%22blobs_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fgit%2Fblobs%7B%2Fsha%7D%22%2C%22git_tags_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fgit%2Ftags%7B%2Fsha%7D%22%2C%22git_refs_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fgit%2Frefs%7B%2Fsha%7D%22%2C%22trees_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fgit%2Ftrees%7B%2Fsha%7D%22%2C%22statuses_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fstatuses%2F%7Bsha%7D%22%2C%22languages_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Flanguages%22%2C%22stargazers_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fstargazers%22%2C%22contributors_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fcontributors%22%2C%22subscribers_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fsubscribers%22%2C%22subscription_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fsubscription%22%2C%22commits_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fcommits%7B%2Fsha%7D%22%2C%22git_commits_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fgit%2Fcommits%7B%2Fsha%7D%22%2C%22comments_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fcomments%7B%2Fnumber%7D%22%2C%22issue_comment_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fissues%2Fcomments%7B%2Fnumber%7D%22%2C%22contents_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fcontents%2F%7B%2Bpath%7D%22%2C%22compare_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fcompare%2F%7Bbase%7D...%7Bhead%7D%22%2C%22merges_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fmerges%22%2C%22archive_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2F%7Barchive_format%7D%7B%2Fref%7D%22%2C%22downloads_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fdownloads%22%2C%22issues_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fissues%7B%2Fnumber%7D%22%2C%22pulls_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fpulls%7B%2Fnumber%7D%22%2C%22milestones_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fmilestones%7B%2Fnumber%7D%22%2C%22notifications_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fnotifications%7B%3Fsince%2Call%2Cparticipating%7D%22%2C%22labels_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Flabels%7B%2Fname%7D%22%2C%22releases_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Freleases%7B%2Fid%7D%22%2C%22deployments_url%22%3A%22https%3A%2F%2Fapi.github.com%2Frepos%2Fwuding%2Fcouponiang%2Fdeployments%22%2C%22created_at%22%3A1532314285%2C%22updated_at%22%3A%222018-08-13T18%3A20%3A29Z%22%2C%22pushed_at%22%3A1534230501%2C%22git_url%22%3A%22git%3A%2F%2Fgithub.com%2Fwuding%2Fcouponiang.git%22%2C%22ssh_url%22%3A%22git%40github.com%3Awuding%2Fcouponiang.git%22%2C%22clone_url%22%3A%22https%3A%2F%2Fgithub.com%2Fwuding%2Fcouponiang.git%22%2C%22svn_url%22%3A%22https%3A%2F%2Fgithub.com%2Fwuding%2Fcouponiang%22%2C%22homepage%22%3A%22http%3A%2F%2Fcouponiang.com%2F%22%2C%22size%22%3A182%2C%22stargazers_count%22%3A0%2C%22watchers_count%22%3A0%2C%22language%22%3A%22PHP%22%2C%22has_issues%22%3Atrue%2C%22has_projects%22%3Atrue%2C%22has_downloads%22%3Atrue%2C%22has_wiki%22%3Atrue%2C%22has_pages%22%3Afalse%2C%22forks_count%22%3A0%2C%22mirror_url%22%3Anull%2C%22archived%22%3Afalse%2C%22open_issues_count%22%3A0%2C%22license%22%3A%7B%22key%22%3A%22apache-2.0%22%2C%22name%22%3A%22Apache+License+2.0%22%2C%22spdx_id%22%3A%22Apache-2.0%22%2C%22url%22%3A%22https%3A%2F%2Fapi.github.com%2Flicenses%2Fapache-2.0%22%2C%22node_id%22%3A%22MDc6TGljZW5zZTI%3D%22%7D%2C%22forks%22%3A0%2C%22open_issues%22%3A0%2C%22watchers%22%3A0%2C%22default_branch%22%3A%22master%22%2C%22stargazers%22%3A0%2C%22master_branch%22%3A%22master%22%7D%2C%22pusher%22%3A%7B%22name%22%3A%22wuding%22%2C%22email%22%3A%22netjoin%40gmail.com%22%7D%2C%22sender%22%3A%7B%22login%22%3A%22wuding%22%2C%22id%22%3A235209%2C%22node_id%22%3A%22MDQ6VXNlcjIzNTIwOQ%3D%3D%22%2C%22avatar_url%22%3A%22https%3A%2F%2Favatars3.githubusercontent.com%2Fu%2F235209%3Fv%3D4%22%2C%22gravatar_id%22%3A%22%22%2C%22url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%22%2C%22html_url%22%3A%22https%3A%2F%2Fgithub.com%2Fwuding%22%2C%22followers_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Ffollowers%22%2C%22following_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Ffollowing%7B%2Fother_user%7D%22%2C%22gists_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Fgists%7B%2Fgist_id%7D%22%2C%22starred_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Fstarred%7B%2Fowner%7D%7B%2Frepo%7D%22%2C%22subscriptions_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Fsubscriptions%22%2C%22organizations_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Forgs%22%2C%22repos_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Frepos%22%2C%22events_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Fevents%7B%2Fprivacy%7D%22%2C%22received_events_url%22%3A%22https%3A%2F%2Fapi.github.com%2Fusers%2Fwuding%2Freceived_events%22%2C%22type%22%3A%22User%22%2C%22site_admin%22%3Afalse%7D%7D';
		$payload = isset($_POST['payload']) ? $_POST['payload'] : urldecode(preg_replace('/^payload=/', '', $php_input)); # echo exit;
		
		// 比较签名、切换事件、解码内容		
		$hash = "sha1=" . hash_hmac('sha1', $php_input, $secret);		
		$cmp = strcmp($signature, $hash);
		if (0 === $cmp) {		
			switch ($github_event) {
				case 'push':
					$result = $this->git_pull();
					break;
				case 'ping':
					break;
				default:
					break;
			}
		} else {
			http_response_code(404);
		}
		$json = json_decode($payload);
		
		// 写入日志
		$path = 'log/';
		$put = file_put_contents($path . time() . '.txt', print_r($GLOBALS, true));
		$log = file_put_contents($path . date('nj') . '.log', print_r($json, true) . PHP_EOL, FILE_APPEND);
		
		unset($secret, $php_input, $payload, $json);
		$this->_json(0, 'test', get_defined_vars());
	}
	
	/**
	 * 拉取
	 */
	public function git_pull()
	{
		$filename = realpath(APP_PATH . '/../docs/pull.bat');
		# $command = file_get_contents($filename);
		$command = <<<HEREDOC
start $filename
HEREDOC;
		$last_line = exec($command, $output, $return_var);
		return get_defined_vars();
	}
}
