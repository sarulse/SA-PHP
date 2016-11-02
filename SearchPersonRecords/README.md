
#SearchPersonRecords#

**Purpose:** To implement a search feature using a valid U.S. phone number and then filter the results using full name and state.

Creates a search form where a user can enter a US phone number to look up a person

**Part 1:**(SearchPersonPart1.php)
* When submitted, a back end XML request then display the results to the user. 
* If no records are found, that information is displayed to the user.
* If there is a problem with the XML server a notice is sent to the user. 
* If there are multiple results, clickable full name and state of each record is displayed. 

[View Part 1](http://sarulsel.us/PHPProjects/SearchPersonPart1.php)


**Part 2:**(SearchPersonPart2.php)
* When a record is clicked, a second xml request is created that will display a list of records with the matching name and state.
  (DisplayDetail.php)
* When a record is selected from the list, it will display detailed information about the record.

Requests and Responses are saved to a MYSQL table for Part 2 (saveResultsToDB.php)


[View Part 2](http://sarulsel.us/PHPProjects/SearchPersonPart2.php)

Note:
----
* Connection Parameters should not be exposed to the public folder (connection.php)
* URL Paramerters with user credentials should not be exposed to the public folder (urlParams.php)
* User input (phone number) is validated both on the client side (JavaScript) and Server side (PHP)

Sample Test inputs:
-----------------
Phone numbers can be entered in the following format:
(123)-4567890 (or) 1234567890 (or) 123-456-7890 (or) 123-4567890

 For Example: 
 (i) 3867540455
(ii) (3867)540456
(iii) 386-7540457
