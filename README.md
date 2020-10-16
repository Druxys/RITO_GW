# RITO GATEWAY API
## WHY
Because Riot Games API Gateway uses limit rate and we can't spam queries, can be pretty boring in development (and production) mode. With this Gateway, you can stock in MySQL brut Riot Games JSON and returns in front the JSON without ANY limit rate.

## HOW IT WORKS

You need an API KEY from Riot Games and put it in the .env file. You can ask a permanent API Key to Riot Games if you are student for example (if not, the API Key will expire fast. Boring for development session)


You need to manually call these routes to get up to date data from Riot Games API.

`// Get data from API`

`localhost:8000/{region}/riot/getHistoryMatchList/{summonerName}`

`localhost:8000/{region}/riot/getHistoryMatch/{idMatch}`


Then you can call as many time as you need the data from the Gateway.

`// Get data from the Gateway`

`localhost:8000/{region}/passerelle/getHistoryMatchList/{summonerName}`

`localhost:8000/{region}/passerelle/getHistoryMatch/{idMatch}`

## THE PROCESS

-> Call Riot Games API

-> Stringify data

-> Insert into MySQL Gateway database

-> Call Gateway API

-> Jsonify data

-> Return data to front

