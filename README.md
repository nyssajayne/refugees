# Vote 1 Refugees
Check politicians and pledge to support refugees this election.

Visit at http://www.vote1refugees.com

# About
Currently, anyone who comes to Australia seeking asylum via boat is subjected to mandatory offshore detention -- people are indefinately kept in detention centres on Manus Island and Nauru without any chance of setting foot in Australia, even if they are deemed a legitimate refugee.

Both the major political parties in Australia support this abhorent practice of detaining people. There is an election coming up in Australia in July and this time, my friend Brendan and I wanted to tell people how to vote for a candidate that will speak up for the asylum seekers who can't.

# The Pledge
Phase 1 of the project involves a pledge, that a user of the site commits to voting for a candidate committed to the interests of those currently detained offshore. The results of the pledge are then reflected back to the users.

We tossed up between collecting signatures ourselves, which would've required a data collection policy, or using an online petition site. change.org provides an API (although it is no longer maintained) and meant that we could focus on the actual message of the site. Keeping the users on the page was important, as there are other resources on the page vital to the cause and we wanted to maintain the users's interest.

# The Postcode Checker
Phase 2 is a postcode checker. A list of current politiicans and electorates is gathered from the API's provided by theyvoteforyou.org and openaustralia.org, users can type in their postcode and check to see their member's position on mandatory dentention in a simple yes, they support refugees or no, they don't manner.

We store information about the policies of each politician. The theyvoteforyou.org politician ID is stored in a MySQL database, along with a number representing yes, no or unknown (the default). A politician's position on this issue isn't always immediately apparent, so a group of volunteers have been diligently contacting them and then updating their position on this issue.

theyvoteforyou.org don't maintain a list of candidates however, so we obtained a list of candidates from the Australian Electoral Commission for the 2016 election.  The volunteers collecting data from current politicians learned that many of them were not very quick to respond to requests for information, but that the parties they were aligned with often had a publicly available policy.  Unless the candidate has a specific stance, the party stance is displayed instead.

# On the Inside
The website is built using Wordpress and at the moment, there is a custom theme and 2 plugins (one for the pledge and one for the postcode tool), as per common MVC conventions. My collaborator Brendan was already familiar with Wordpress, plus I knew I could develop rapidly -- the election was called swiftly, it would usually happen in September.

The site is hosted on a Digital Ocean Ubuntu server running Nginx, which, coupled with the plugin WP Super Cache, ensures the site is loaded as quickly as possible with minimal expense on the server.

# On the Outside
The header image and overall art direction was provided by an illustrator, who provided all the assets, font requirements and colour schemes. The decision to go with a very traditional layout was to ensure the site could be scanned quickly by someone browsing and easily understood and engaged with.

This is an ongoing project and while the current use is very specific, I believe there is scope to fork the plugin and continue developing these for broader applications.
