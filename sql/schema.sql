# Creates dataset table, with the columns id, name, protein family, taxon, whether it is the
# example data, the session id and when it was created.
CREATE TABLE datasets (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255),
	protein_family VARCHAR(255),
	taxon VARCHAR(255),
	example BOOLEAN DEFAULT 0,
	session_id VARCHAR(255),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
# Creates sequences table, with the columns id, dataset id, accession code, species, sequence and 
# length. Foreign key to datasets table is used to ensure that the same dataset is being accessed.
CREATE TABLE sequences (
	id INT AUTO_INCREMENT PRIMARY KEY,
	dataset_id INT,
	accession VARCHAR(100),
	species VARCHAR(255),
	sequence TEXT,
	length INT,
	FOREIGN KEY (dataset_id) REFERENCES datasets(id)
);
# Creates analysis table, with the columns id, dataset id, type of analysis, result path, when  it
# was created. Again uses foreign key to datasets table for standardisation.
CREATE TABLE analysis (
	id INT AUTO_INCREMENT PRIMARY KEY,
	dataset_id INT,
	type VARCHAR(100),
	result_path TEXT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (dataset_id) REFERENCES datasets(id)
);
# Creates motifs table, with columns id, sequence id, motif name, start position and end position.
# Foreign key to sequences table.
CREATE TABLE motifs (
	id INT AUTO_INCREMENT PRIMARY KEY,
	sequence_id INT,
	motif_name VARCHAR (255),
	start_position INT,
	end_position INT,
	FOREIGN KEY (sequence_id) REFERENCES sequences(id)
);
