<?php

/*
 * 申请 access_token 或者刷新 access_token.
 */
$router->post('oauth/access_token', 'OauthController@issueAccessToken');
