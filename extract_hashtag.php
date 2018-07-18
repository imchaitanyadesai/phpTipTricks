function extract_hashtags($string) {
 
 /* Match hashtags */
 preg_match_all('/#(\w+)/', $string, $matches);
 
  /* Add all matches to array */
  foreach ($matches[1] as $match) {
    $keywords[] = $match;
  }
 
 return (array) $keywords;
}
