CREATE TABLE
    listName (
        listNameId INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
        listName VARCHAR(255),
        dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE
    listContent (
        listContentId INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
        listNameId INT,
        listContent TEXT,
        isDone BOOLEAN DEFAULT FALSE,
        dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (listNameId) REFERENCES listName (listNameId)
    );