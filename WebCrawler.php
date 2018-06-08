<?php

// This class allows to obtain the information from every web page that it visits.
class WebCrawler{
    
    private $main_url;      // Main url (Example: "https://en.wikipedia.org").
    private $current_url;   // Current visited url from the main url.
    private $txt_file;      // Name of the txt file where the data is saved.
    private $max_depth;     // Max number of pages that can be visited.
    private $current_depth; // Number of pages already visited.
    private $all_links;     // Links extracted from every page visited.
    
    // Constructor.
    public function __construct($main_url, $txt_file, $max_depth){
        $this->main_url = $main_url;
        $this->current_url = $main_url;
        $this->txt_file = $txt_file;
        $this->max_depth = $max_depth;
        $this->current_depth = 0;
        $this->all_links = array();
    }
    
    // Writes the page contents into a txt file.
    // Parameters: the contents that have to be written into the txt file.
    public function writeFile($contents){        
        $openFile = fopen($this->txt_file, "a");
        fwrite($openFile, $contents . PHP_EOL);
        fclose($openFile);
    }
        
    // Gets the page contents and saves them into the txt file.
    // PONER STATIC PARA RECURSIVIDAD
    public function getContents(){
        // If the limit is reached.
        if($this->current_depth == $this->max_depth){
            $this->current_depth = 0;            
            return;
        }
        else{
            // Gets the page contents and saves them like a string.
            $html = file_get_contents($this->current_url); 
            $doc = new DOMDocument(); 
            @$doc->loadHTML($html);
            $xpath = new DOMXpath($doc);

            // Gets the contents from the div tags.
            $items = $xpath->query("//div");

            // Gets the page title and writes it in the txt file.
            $page_title = $this->getPageTitle();
            $this->writeFile($page_title . PHP_EOL);

            // Gets the value of each DOMDocumentNode and joins it to $pageContents.                        
            for($i = 0; $i < 5; $i++){
                $this->writeFile($items[$i]->nodeValue . PHP_EOL);
                echo $items[$i]->nodeValue, PHP_EOL;        
            }
            
            // choose current_url randomly
                          
            $this->current_depth++;            
            $this->getContents();
        }        
    }        
    
    // Gets the links from the current page and saves them into an array.
    public function getLinks(){
        $html = file_get_contents($this->current_url); 
        preg_match_all('/<a href="(.*?)"/', $html, $matches); // Inserts all the found links into an array.                
        
        // Appends every found link to the array "all_links".
        foreach($matches[1] as $m){
            $is_link = strpos($m, "http"); // To verify if it's already a valid link (http in it).
            
            // If it's not a valid link, it adds the main url before the link.
            if($is_link === false){
                array_push($this->all_links, $this->main_url . $m);                
                /*echo $this->main_url . $m . PHP_EOL;*/
            }
            // If it's a valid link.
            else{
                array_push($this->all_links, $m);   
                /*echo $m . PHP_EOL;*/
            }            
        }        
    }
    
    // Gets the title from the current page.
    public function getPageTitle(){
        $html = file_get_contents($this->current_url);
        preg_match('/<title>(.*)<\/title/i', $html, $title);
        $title_out = $title[1];
        return $title_out;
    }
}

$crawler = new WebCrawler("https://en.wikipedia.org", "PagesContents.txt", 1);
$crawler->getContents();
$crawler->getLinks();    