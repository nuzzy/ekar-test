# Test Project For Ekar

## How To Use

1. On your local computer clone code to local directory `git clone git@github.com:nuzzy/ekar-test.git`.
2. Move to that directory and run Docker-compose `docker-compose up -d`.
3. This will run local server and expose it to ports 2222 (HTTP) and 443 (HTTPS).
4. Create your new Slack Workspace at https://slack.com/create.
5. Register Slack App at https://api.slack.com/apps.
6. Follow the instruction on the page https://api.slack.com/apps/A019DHT530F/oauth? and setup OAuth Permissions and Scopes: `chat:write`, `im:history`, `im:read` and `im:write`.
7. Enable and setup Events API at https://api.slack.com/apps/A019DHT530F/event-subscriptions? with subscription to bot event `message.im`. 
8. To enter the `Request URL` use *ngrok* utils (see link) to expose localhost server. Setup URL to `https://8279b1ef3153.ngrok.io/slack-bot/euler-problem-1` (This part `8279b1ef3153` will be different for you).
9. Add the Slack Bot to your Workspace.
10. Write a personal message directly to your bot like `10`.
