# The Leaderboard API
**For Canada Drives by Kareem Areola**

This REST API provides endpoints to view and control a leaderboard.

Simply make JSON requests as described to use the API

## Setup:

Place these files on a webserver running PHP in order to run the API. 


## Usage:

### Endpoints:

#### **/public/list/**
Provides the list of players ordered by highest score to lowest.

##### Parameters:
None

##### Returns:
JSON List of players

#### **/public/players/upscore**
Increases the score by one point of a given player.

##### Parameters:
{
"playerId" : ...
}

##### Returns:
**true** if successful
**false** if failed

#### **/public/players/downscore**
Decreases the score by one point of a given player.

##### Parameters:
{<br />
"playerId" : ...<br />
}

##### Returns:
**true** if successful
**false** if failed


#### **/public/players/add**
Add a player to the system given the name, address and age.

##### Parameters:

<br />
{<br />
    "playerName": "James Franco",<br />
    "playerAge": 99,<br />
    "playerAddress" : "99 Franco Blvd"<br />
}

##### Returns:
**true** if successful
**false** if failed

#### **/public/players/remove**
Remove a player to the system given the playerId

##### Parameters:
{<br />
"playerId" : ...<br />
}

##### Returns:
**true** if successful
**false** if failed

