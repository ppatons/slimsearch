Slim Search Engine
===================

Search for Slim Framework


Word index
----------------------
Build a search index. This is a table indexing all occurrences of a particular word so that search engine can find it.


Splitting phrases into words
----------------------
The content used to build the index is a set of phrases like about, company and tags. We need a list of words. So, we need to split the phrases into words, ignoring all punctuation, numbers, and putting all words to lowercase. The str_word_count() PHP function comes in handy.

// split into words
$words = str_word_count(strtolower($phrase), 1);


Stop words words
----------------------
Some words, like "or," "and," "the," "we,", "again", "further," and "also", should be ignored when indexing text content. They appear in almost every text, and make the search return a lot of poorly interesting results that have nothing to do with a user's query. These stop words are specific to a given human language.

We can store the stop words in a database table or an array method file.


Stemming
----------------------
Words having the same radical should be seen as a single one. 'houses' should increase the weight of 'house', as should 'book' do for 'books'. Before indexing words, they should be reduced to their greatest common divisor, and in linguistics vocabulary, this is called a stem, or "the base part of the word including derivational affixes but not inflectional morphemes, i. e. the part of the word that remains unchanged through inflection".

There are lots of rules to transform a word into its stem, and these rules are all language-dependant. One of the best stemming techniques for the English language so far is called the Porter Stemming Algorithm and, as we are very lucky, it has been ported to PHP5 in an open-source script available from tartarus.org.

The PorterStemmer class provides a ::stem($word) method that is perfect for our needs. So we can write a method,  that turns a phrase into an array of stem words:


Giving weight to words
----------------------
Search results have to appear in order of pertinence. The profile that are more tightly related to the words entered by the user have to appear first. We need to translate this idea of pertinence into an algorithm? 

	- If a searched word appears in the name of a profile, this profile should appear higher in a search result than 	another one where the word appears only in the about body.

    - If a searched word appears twice in the about content of a profile, the search result should show this profile before others where the word appears only once.

	- This is why we need to give weight to words according to the part of the profile they come from. As the weight factors have to be easily accessible, to make them vary if we want to fine tune our search engine algorithm, we will put them in the application database in the wordindex table
	
The basic weight of the words will be given by their number of occurrences in the text and we can use the array_count_values() PHP function for that.


Updating the word index
----------------------
The index has to be updated each time a profile, tag or text is added. We also have to update the profile index each time a tag is added to it.


The search function
---------------------
AND or OR?

The search function should manage both 'AND' and 'OR' searches. If a user enters 'university lecturer', the user must be given the choice to look only for the profile where both the two terms appear (that's an 'AND'), or for all the profiles where at least one of the term appears (that's an 'OR'). The trouble is that these two options lead to different queries.


The search method
---------------------
We need to apply the same treatment to the search phrase as to the content, so that the words entered by the user are reduced to the same kind of stem that lies in the index. 