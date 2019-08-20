//codeigniter
$queary_mode = "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
$queary_mode = $this->db->query($queary_mode);
