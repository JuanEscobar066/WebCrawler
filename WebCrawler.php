<?php

// This class allows to obtain the information from every web page that it visits.
class WebCrawler{
    
    private $main_url;      // Main url (Example: "https://en.wikipedia.org").
    private $current_url;   // Current visited url from the main url.
    private $txt_file;      // Name of the txt file where the data is saved.
    private $max_depth;     // Max number of pages that can be visited.
    private $current_depth; // Number of pages already visited.
    private $all_links;     // Links extracted from every page visited.
    private $visited_links; // All the already visited links.
    
    // Constructor.
    public function __construct($main_url, $txt_file, $max_depth){
        $this->main_url = $main_url;
        $this->current_url = $main_url;
        $this->txt_file = $txt_file;
        $this->max_depth = $max_depth;
        $this->current_depth = 0;
        $this->all_links = array();
        $this->visited_links = array();     
        set_time_limit(2000);
    }
    
    // Writes the page contents into a txt file.
    // Parameters: the contents that have to be written into the txt file.
    public function writeFile($contents){        
        $openFile = fopen($this->txt_file, "a");
        fwrite($openFile, $contents . PHP_EOL);
        fclose($openFile);
    }
        
    // Gets the page contents and saves them into the txt file.    
    public function getContents(){        
        // If the limit is reached.
        if($this->current_depth == $this->max_depth){
            $this->current_depth = 0;                        
            print_r($this->visited_links);
        }
        else{            
            // Gets the page contents and saves them like a string.
            $html = @file_get_contents($this->current_url); 
            $doc = new DOMDocument(); 
            @$doc->loadHTML($html);
            $xpath = new DOMXpath($doc);

            array_push($this->visited_links, $this->current_url); // Saves the current link as visited in the array.            
            $items = $xpath->query("//div");                      // Gets the contents from the div tags.                                    

            // Gets the page title and writes it in the txt file.
            $page_title = $this->getPageTitle();
            
            // Verifies if the title could be obtained.
            if($page_title !== false){                            
                $this->writeFile("$--beginning--$");               // Separator.
                $this->writeFile($this->current_depth . " " . $page_title);                  // Writes the title in the txt file.
                $this->writeFile($this->current_url . PHP_EOL); // Gets the link of the current page and writes it in the txt file.

                echo "$--beginning--$" . "<br>";       
                echo $this->current_depth . " " . $page_title . "<br>";
                echo $this->current_url . "<br>";       

                // Gets the value of each DOMDocumentNode and writes it in the txt file.                        
                for($i = 0; $i < 5; $i++){         
                    // If it's a valid node.
                    if(isset($items[$i])){
                         $this->writeFile($items[$i]->nodeValue . PHP_EOL);    
                    }                                    
                }                                                
                $this->current_depth++; // Increments the depth. 
            }            
            $this->getLinks();                           // Gets the links included in the current page.            
            $this->current_url = $this->getRandomLink(); // Gets a random link.              
            return $this->getContents();                         
        }        
    }        
    
    // Gets a random link from the extracted links.
    public function getRandomLink(){
        // Randomly, chooses a new url.        
        $randomIndex = rand(0, count($this->all_links) - 1);
        $randomLink = $this->all_links[$randomIndex];    
        
        // If the random link chosen has not been visited yet.
        if(in_array($randomLink, $this->visited_links) == false){
            /*echo 'shrandom: ' . $randomLink;*/
            return $randomLink;
        }
        else{
            return $this->getRandomLink();
        }
    }
    
    // Gets the links from the current page and saves them into an array.
    public function getLinks(){
        $html = @file_get_contents($this->current_url); 
        preg_match_all('/<a href="(.*?)"/', $html, $matches); // Inserts all the found links into an array.                
        
        // Appends every found link to the array "all_links".
        foreach($matches[1] as $m){
            $is_link = strpos($m, 'http') or strpos($m, 'www'); // To verify if it's already a valid link (http or www in it).
            
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
        $html = @file_get_contents($this->current_url);
        preg_match('/<title>(.*)<\/title/i', $html, $title);
        
        // If the title could be obtained.
        if(isset($title[1])){            
            return $title[1];
        }
        return false;        
    }
}

$crawler = new WebCrawler("https://en.wikipedia.org", "PagesContents.txt", 500);
$crawler->getContents();

