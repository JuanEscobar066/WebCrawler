/*
**************************************************
Source code:    WordsCount.java
Version:        1.0
Creation Date:  09/may/2018
Last update:    N/A.
Author:         Jorge Barquero Villalobos,
                Juan Escobar Sánchez.
                Iván López Saborío,
                based on Ing. Erick Hernández Bonilla.
Description:    code that can count all the words in a text. Example: 
<Dog, 4>
<Red, 16>
...
Note:           Using Windows 10, JDK 1.8.0_112 and Hadoop 2.7.3. 
Built in:       NetBeans 8.2.
**************************************************
*/
package WordCount;

// Java libraries to manage Arrays, Exceptions and Tokens.
import java.io.IOException;
import java.util.ArrayList;
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


public class WordCount{
    
    // This class can tokenize a file and create a map based on what it need. 
    // In this case, creates a map of <word, one>.
    public static class TokenizerMapper
        extends Mapper<Object, Text, Text, IntWritable>
    {
        // This atributte can write a one in a file. (1).  
        private final static IntWritable one = new IntWritable(1);
        // This is a copy of the word, in this can grab the token and send it 
        // to write in a file.
        private Text word = new Text();
        
        // In this mapper, he runs through the file scanning all the tokens 
        // and creating a map that is: <Word, 1>.
        public void map(Object key, Text value, Context context)
                    throws IOException, InterruptedException
        {
            // This is the tokenizer that can scan all the words.
            StringTokenizer itr = new StringTokenizer(value.toString());
            
            // Go an search in all the text.
            while(itr.hasMoreTokens()){
                word.set(itr.nextToken());  // Example: Word = "Red".
                context.write(word, one);   // Save it like: <"Red", 1>.
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
    job.setJarByClass(WordCount.class);
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
