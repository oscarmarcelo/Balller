# Dribbble API PHP Wrapper
*by [Oscar Marcelo](http://oscarmarcelo.com)*

A PHP wrapper for the [Dribbble API v1](http://developer.dribbble.com/v1/).

## How to use
 1. An application is now required. [Register](https://dribbble.com/account/applications/new) a new one.
 2. There are two ways to use the API.
  - Using an OAuth authorization code, which gives full access to the API (Coming soon).
  - Using the Client Access Token, which gives a read-only access to the API.

 3. Paste the following code. Replace with you token, and include file path, if needed:
```php
require 'Dribbble-lite.php';
$token = 'YourTokenHere';
$dribbble = new Dribbble($token);
```
 4. Using the wrapper methods is easy as:
```php
$dribbble->request('user/shots', array('per_page' => 20));
``` 
 5. Good Work! :)

##Methods

 - request($endpoint, $params = array())
 - bucket($id, $params = array())
 - bucket_shots($id, $params = array())
 - project($id, $params = array())
 - project_shots($id, $params = array())
 - shots($list = false, $timeframe = false, $date = false, $sort = false, $params = array())
 - shot($id)
 - shot_attachments($id, $params = array())
 - attachment($shot_id, $attachment_id)
 - shot_buckets($id)
 - shot_comments($id, $params = array())
 - comment_likes($shot_id, $comment_id, $params = array())
 - comment($shot_id, $comment_id)
 - has_liked_comment($shot_id, $comment_id)
 - shot_likes($id, $params = array())
 - has_liked_shot($id)
 - shot_projects($id, $params = array())
 - shot_rebounds($id, $params = array())
 - team_members($team, $params = array())
 - team_shots($team, $params = array())
 - user($user, $params = array())
 - current_user($params = array())
 - user_buckets($user, $params = array())
 - current_user_buckets($params = array())
 - user_followers($user, $params = array())
 - current_user_followers($params = array())
 - user_following($user, $params = array())
 - current_user_following($params = array())
 - current_user_following_shots($params = array())
 - is_current_user_following($user)
 - is_user_following($user, $target_user)
 - user_likes($user, $params = array())
 - current_user_likes($params = array())
 - user_projects($user, $params = array())
 - current_user_projects($params = array())
 - user_shots($user, $params = array())
 - current_user_shots($params = array())
 - user_teams($user, $params = array())
 - current_user_teams($params = array())

##Response
The wrapper return the responses as PHP objects.  
If any errors are encountered, then a `Exception` is thrown, which can be caught in a `try`/`catch` block.

##To do

 - Do a full version (with authorization) of the wrapper