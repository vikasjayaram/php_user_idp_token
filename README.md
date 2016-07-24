# php_user_idp_token
A simple php script to demonstrate the use of user idp token saved in Auth0

### Configuration

* `tenant_name` : Name of the Auth0 tenant
* `account_client_id` : Non Interactive Client with `read:user_idp_token` scope enabled
* `account_client_secret` : Non Interactive client secret
* `user_id` : the identifier of the user you want to fetch the IDP access token/secret
* `idp_api` : The IDP URL you want to call on behalf of the user.
