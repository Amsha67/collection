SELECT * FROM collections; --   Affiche toutes les collections disponibles

SELECT * FROM elements_collection; -- Affiche tous les éléments de la collection avec leurs détails

SELECT titre_element, numero FROM elements_collection; -- Affiche uniquement les titres et les numéros des éléments de la collection

SELECT * FROM types_collection; -- Affiche les types de collection disponibles

SELECT * FROM elements_collection WHERE possede = 1; -- Affiche les éléments que je possède

SELECT * FROM elements_collection WHERE possede = 0; -- Affiche les éléments que je ne possède pas

SELECT * FROM elements_collection WHERE numero > 2; -- Affiche les éléments dont le numéro est supérieur à 2

SELECT * FROM elements_collection WHERE titre_element LIKE '%Dark%'; -- Affiche les éléments dont le titre contient "Dark"

SELECT * FROM elements_collection WHERE possede = 1 AND numero > 1; -- Affiche les éléments que je possède et dont le numéro est supérieur à 1

SELECT * FROM elements_collection WHERE possede = 0 OR numero > 2; -- Affiche les éléments que je possède ou ceux dont le numéro est supérieur à 2

SELECT * FROM elements_collection ORDER BY numero ASC; -- Tri par numéro croissant

SELECT * FROM elements_collection ORDER BY numero DESC; -- Tri par numéro décroissant

SELECT * FROM elements_collection ORDER BY titre_element ASC; -- Tri par titre d'élément par ordre alphabétique 



-- Affiche lee nom de la collection et le type de collection
SELECT c.nom_collection, t.nom_type-- Sélectionner le nom de la collection et le type

FROM collections c -- Table principale : collections avec l'alias "c"

JOIN types_collection t -- Jointure avec types_collection pour récupérer le nom du type

ON c.id_type = t.id_type; -- Condition de liaison : id_type identique dans les deux tables

-- Affiche le nom de la collection et le type de collection
SELECT e.titre_element, c.nom_collection 
FROM elements_collection e 
JOIN collections c ON e.id_collection = c.id_collection;

-- Affiche le titre de l'élément le numéro et le nom de la collection à laquelle il appartient
SELECT e.titre_element, e.numero, c.nom_collection
FROM elements_collection e
JOIN collections c ON e.id_collection = c.id_collection;

-- Affiche le titre de l'élément, le nom de la collection et le type de collection
SELECT e.titre_element, c.nom_collection, t.nom_type
FROM elements_collection e
JOIN collections c ON e.id_collection = c.id_collection
JOIN types_collection t ON c.id_type = t.id_type;

-- Affiche le titre de l'élément et le nom de la personne qui l'a emprunté
SELECT e.titre_element, n.nom_emprunter
FROM elements_collection e
JOIN emprunter n ON e.id_element = n.id_element;

-- Affiche le nom de l'emprunteur, le titre de l'élément emprunté, le nom de la collection et la date d'emprunt
SELECT emp.nom_emprunteur, e.titre_element, c.nom_collection, emp.date_emprunt
FROM emprunts emp
JOIN elements_collection e ON emp.id_element = e.id_element
JOIN collections c ON e.id_collection = c.id_collection;

-- Affiche les éléments empruntés qui n'ont pas encore été retournés
SELECT * FROM emprunts WHERE date_retour IS NULL;

-- Affiche le nombre total d'éléments dans la collection
SELECT COUNT(*) FROM elements_collection;

-- Affiche le nombre d'éléments que je possède
SELECT COUNT(*) FROM elements_collection WHERE possede = 1;

-- Affiche le numéro max de la collection
SELECT MAX(numero) FROM elements_collection;

-- Affiche la moyenne des numéros 
SELECT AVG(numero) FROM elements_collection;
SELECT ROUND(AVG(numero)) FROM elements_collection; -- Affiche la moyenne des numéros arrondie à l'entier le plus proche

-- Affiche le nombre d'éléments par collection
SELECT c.nom_collection, COUNT(e.id_element) AS nombre_elements
FROM collections c
JOIN elements_collection e ON c.id_collection = e.id_collection
GROUP BY c.id_collection;

--  Affiche le nombre de collection par type 
SELECT t.nom_type, COUNT(c.id_collection) AS nombre_collections 
FROM types_collection t 
JOIN collections c ON t.id_type = c.id_type 
GROUP BY t.id_type;