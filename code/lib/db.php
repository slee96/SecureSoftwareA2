<?php
$dbconn = pg_pconnect("host=$pg_host port=$pg_port dbname=$pg_dbname user=$pg_dbuser password=$pg_dbpassword") or die("Could not connect");
if ($debug) {
	echo "host=$pg_host, port=$pg_port, dbname=$pg_dbname, user=$pg_dbuser, password=$pg_dbpassword<br>";
	$stat = pg_connection_status($dbconn);
	if ($stat === PGSQL_CONNECTION_OK) {
		echo 'Connection status ok';
	} else {
		echo 'Connection status bad';
	}    
}

function run_query($dbconn, $query) {
	if ($debug) {
		echo "$query<br>";
	}
	$result = pg_query($dbconn, $query);
	if ($result == False and $debug) {
		echo "Query failed<br>";
	}
	return $result;
}

// function for prepared statments 
function run_query_prepared($dbconn, $query_name, $array) {
	if ($debug) {
		echo "$query_name<br>";
	}
	$result = pg_execute($dbconn, $query_name, $array);
	//deallocate prepared statment name
	pg_query($dbconn, "deallocate " . $query_name);
	if ($result == False and $debug) {
		echo "Query failed<br>";
	}
	return $result;	
}


//database functions
function get_article_list($dbconn){
	// Dont need prepared statment for this function 
	$query = "SELECT 
		articles.created_on as date,
		articles.aid as aid,
		articles.title as title,
		authors.username as author,
		articles.stub as stub
		FROM
		articles
		INNER JOIN
		authors ON articles.author=authors.id
		ORDER BY
		date DESC";
	return run_query($dbconn, $query);
}

function get_article($dbconn, $aid) {
	pg_prepare($dbconn, "get_article", "SELECT 
		articles.created_on as date,
		articles.aid as aid,
		articles.title as title,
		authors.username as author,
		articles.stub as stub,
		articles.content as content
		FROM 
		articles
		INNER JOIN
		authors ON articles.author=authors.id
		WHERE
		aid= $1
		LIMIT 1");
	return run_query_prepared($dbconn, "get_article", array(htmlspecialchars($aid)));
}

function delete_article($dbconn, $aid) {
	pg_prepare($dbconn, "delete_article", "DELETE FROM articles WHERE aid= $1 ");
	return run_query_prepared($dbconn, "delete_article", array(htmlspecialchars($aid)));
}

function add_article($dbconn, $title, $content, $author) {
	$stub = substr($content, 0, 30);
	$aid = str_replace(" ", "-", strtolower($title));
	pg_prepare($dbconn, "add_article", "
		INSERT INTO
		articles
		(aid, title, author, stub, content) 
		VALUES
		($1 , $2 , $3 , $4, $5)");
	return run_query_prepared($dbconn, "add_article", array($aid, $title, $author, $stub, $content));
}

function update_article($dbconn, $title, $content, $aid) {
	pg_prepare($dbconn, "add_article", "UPDATE articles SET title= $1, content= $2 WHERE aid= $3"); 
	return run_query_prepared($dbconn, "add_article", array(htmlspecialchars($title), htmlspecialchars($content), htmlspecialchars($aid)));
}

function authenticate_user($dbconn, $username, $password) {
	// Also used prepated statment to mitigate sql injections
	if(filter($username) && filter($password)){
		pg_prepare($dbconn, "auth_user", "SELECT authors.id as id, authors.username as username, authors.password as password, authors.role as role FROM authors WHERE username= $1 AND password= $2 LIMIT 1");
		return run_query_prepared($dbconn, "auth_user", array($username, $password));
	}
    return "banana";
}	

function authenticate_article_owner($dbconn, $username, $aid) {
	if(filter($username)){
		//return one row where the article name and owner are the same
		pg_prepare($dbconn, "authenticate_article_owner", "SELECT authors.id as id, authors.username as username, authors.role as role FROM authors JOIN articles on authors.id=articles.author WHERE authors.username= $1 AND articles.aid= $2 LIMIT 1");
		return run_query_prepared($dbconn, "authenticate_article_owner", array($username, $aid));
	}
    return "banana";
}

function filter($input){
	// Created an 'if' statment using htmlspecialchars() and regex match to catch any special charcters not caught by htmlspecialchars()
	if(!preg_match('/^(["\'\;#]).*\1$/m', $input) && htmlspecialchars($input, ENT_QUOTES) == $input){
		return true;	
	}else{
		return false;
	}
}
?>
