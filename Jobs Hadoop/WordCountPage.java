/*
**************************************************
Source code:    WordsCountPage.java
Version:        1.0
Creation Date:  09/may/2018
Last update:    N/A.
Author:         Jorge Barquero Villalobos,
                Juan Escobar Sánchez.
                Iván López Saborío,
                based on Ing. Erick Hernández Bonilla.
Description:    code that can count all the words in a page. Example:
www.facebook.com has:
<Dog, 4>
<Red, 16>
...
Note:           Using Windows 10, JDK 1.8.0_112 and Hadoop 2.7.3. 
Built in:       NetBeans 8.2.
**************************************************
*/
package wordcountpage;

// Java libraries to manage Arrays, Exceptions and Tokens.
import java.io.IOException;
import java.util.StringTokenizer;

// Libraries of Hadoop.
// Their Jars are included in Hadoop, one have to add them 
// manually.
import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Job;
import org.apache.hadoop.mapreduce.Mapper;
import org.apache.hadoop.mapreduce.Reducer;
import org.apache.hadoop.mapreduce.lib.input.FileInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;

// This main class contains the Map-reduce to count all the words of a page.
public class WordCountPage {
    // This class can tokenize a file and create a map based on what it need. 
    // In this case, creates a map of <word, (one, url)>.
    public static class TokenizerMapper
        extends Mapper<Object, Text, Text, IntWritable>
    {
        // This atributte can write a one in a file. (1).  
        private final static IntWritable one = new IntWritable(1);
        // This is a copy of the word, in this can grab the token and send it 
        // to write in a file.
        private Text word = new Text();
        private Text url  = new Text();
        private Text aux  = new Text();
        private int counter;    // Counter to find the URL of the page.
        
        
        // In this mapper, he runs through the file scanning all the tokens 
        // and creating a map that is: <Word, (1, url)>.
        public void map(Object key, Text value, Context context)
                    throws IOException, InterruptedException
        {
            
            // This is the tokenizer that can scan all the words.
            StringTokenizer itr = new StringTokenizer(value.toString());
            
            // Go and map-reduce all the text.
            while(itr.hasMoreTokens()){
                
                // Obtain the token.
                aux.set(itr.nextToken());
                
                // It means that he found a new page, so reset the counter.
                if((aux.toString()).contains("$--beginning--$")){
                    counter = 0;
                }
                
                // If the counter is < 1, it means that he's reading the 
                // title of the page.
                else if (counter < 1){
                    counter++;
                    // Don't do anything. Just read the title.
                }
                
                // He found the url of the file, so set it to the url.
                else if(counter == 1){
                    url.set(aux);
                    counter++;
                }
                
                // It means that the counter is > 1h, so map the words.
                else{
                    word.set(aux + "-" + url);  // Example: Word = "Red".
                    context.write(word, one);   // Save it like: <"Red", 1>.
                } 
            }            
        }
    }
    
    // This class sums up the result of counting all the words in one 
    // pair <Key, Value>. Example: <"Red", 15>.
    public static class intSumReducer extends 
            Reducer<Text,IntWritable,Text, IntWritable>
    {
        // This can save any number.
        private IntWritable result = new IntWritable();
        
        // This is the reducer of the file.
        public void reduce(Text key, Iterable<IntWritable> values, 
                            Context context) throws IOException, InterruptedException 
        {
            // Everytime he finds a <Word, One>, sum += 1.
            int sum = 0;
            for(IntWritable val : values){
                sum += val.get();
            }
            result.set(sum); // Result becomes sum. Example: Result = 12.
            // Now he writes to the file
            context.write(key, result); 
        }
    }
   
    public static void main(String[] args) throws Exception{
        // All the procedure that involves the Map-Reduce.
        Configuration conf = new Configuration();
        Job job = Job.getInstance(conf, "word count");
        job.setJarByClass(WordCountPage.class);
        job.setMapperClass(TokenizerMapper.class);
        job.setCombinerClass(intSumReducer.class);
        job.setReducerClass(intSumReducer.class);
        job.setOutputKeyClass(Text.class);
        job.setOutputValueClass(IntWritable.class);
        FileInputFormat.addInputPath(job, new Path(args[1]));
        FileOutputFormat.setOutputPath(job, new Path(args[2]));
        System.exit(job.waitForCompletion(true) ? 0 : 1);
        
    }
}
    

