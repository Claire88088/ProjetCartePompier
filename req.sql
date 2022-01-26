
/* peuplement users */
/* delete from users; */
insert into users('nom', 'roles', 'password') values ('admin', '["ROLE_ADMIN"]', '$2y$13$42J55tM7N4R0jdqRc2/4DOrc9o0nt/7huhPRHBalH8bnO0bJ1T3D6');
insert into users('nom', 'roles', 'password') values ('user', '["ROLE_USER"]', '$2y$13$eD2Ou25gPvOIh4W203mOwe1IyEkN0ttPM309ZD4FfSxD0dMKwmqFW');


/* peuplement commune */
/* DELETE FROM commune; */
insert into commune('nom', 'code_postal', 'latitude', 'longitude') values ('Buxerolles', '86180', 46.5985617, 0.352332);
insert into commune('nom', 'code_postal', 'latitude', 'longitude') values ('Poitiers', '86000', 46.580224, 0.340375);
insert into commune('nom', 'code_postal', 'latitude', 'longitude') values ('Châtellerault', '86100', 46.816487, 0.548146);

/* peuplement icone */
insert INTO icone('lien') VALUES ('icons8-bâtiment-24.png');
insert INTO icone('lien') VALUES ('icons8-essence-24.png');
insert INTO icone('lien') VALUES ('icons8-gaz-24.png');
insert INTO icone('lien') VALUES ('icons8-usine-24.png');
insert INTO icone('lien') VALUES ('icons8-échelle-24.png');

/* peuplement type_calque */
/* DELETE FROM type_calque;*/
insert into type_calque('id', 'nom', 'type') VALUES (1, 'Etablissements Répertoriés', 'ER');
insert into type_calque('id', 'nom', 'type') VALUES (2, 'Travaux', 'TRAVAUX');
insert into type_calque('id', 'nom', 'type') VALUES (3, 'Autoroute', 'AUTOROUTE');
insert into type_calque('id', 'nom', 'type') VALUES (4, 'Poteaux Incendie', 'PI');
insert into type_calque('id', 'nom', 'type') VALUES (5, 'Piscines', 'AUTRE');

/* peuplement type_element */
insert INTO type_element('id', 'type_calque_id', 'nom', 'type') VALUES (1, 1, 'Immeuble', 'IMMEUBLE');
insert INTO type_element('id', 'type_calque_id', 'nom', 'type') VALUES (2, 1, 'Industrie', 'INDUSTRIE');
insert INTO type_element('id', 'type_calque_id', 'nom', 'type') VALUES (3, 2, 'Travaux', 'TRAVAUX');
insert INTO type_element('id', 'type_calque_id', 'nom', 'type') VALUES (4, 3, 'Accès barrière de sécurité', 'ACCES');
insert INTO type_element('id', 'type_calque_id', 'nom', 'type') VALUES (5, 3, 'Echangeur', 'ECHANGEUR');
insert INTO type_element('id', 'type_calque_id', 'nom', 'type') VALUES (6, 3, 'Point kilométrique', 'PK');
insert INTO type_element('id', 'type_calque_id', 'nom', 'type') VALUES (7, 4, 'Poteau incendie', 'PI');
insert INTO type_element('id', 'type_calque_id', 'nom', 'type') VALUES (8, 1, 'Ecole Maternelle', 'AUTRE');
insert INTO type_element('id', 'type_calque_id', 'nom', 'type') VALUES (9, 3, 'Element Auto', 'AUTRE');


/* peuplement point */
/* delete from point;*/
insert into point('element_id', 'latitude', 'longitude', 'rang') values (1, 46.832612, 0.552458, 1);
insert into point('element_id', 'latitude', 'longitude', 'rang') values (2, 46.812177, 0.554433, 1);
insert into point('element_id', 'latitude', 'longitude', 'rang') values (3, 46.828285, 0.538806, 1);
insert into point('element_id', 'latitude', 'longitude', 'rang') values (4, 46.815657, 0.556586, 1);
insert into point('element_id', 'latitude', 'longitude', 'rang') values (5, 46.825657, 0.556586, 1);


/* peuplement element */
insert into element('id', 'type_element_id', 'icone_id', 'photo', 'texte', 'lien', 'date_deb', 'date_fin')
values (1, 1, 1, NULL, 'Ehpad Le Village', NULL, NULL, NULL);
insert into element('id', 'type_element_id', 'icone_id', 'photo', 'texte', 'lien', 'date_deb', 'date_fin')
values (2, 2, 1, NULL, 'industrie 1', NULL, NULL, NULL);
insert into element('id', 'type_element_id', 'icone_id', 'photo', 'texte', 'lien', 'date_deb', 'date_fin')
values (3, 2, 4, NULL, 'industrie 2', NULL, NULL, NULL);
insert into element('id', 'type_element_id', 'icone_id', 'photo', 'texte', 'lien', 'date_deb', 'date_fin')
values (4, 2, 4, NULL, 'industrie 3', NULL, NULL, NULL);
insert into element('id', 'type_element_id', 'icone_id', 'photo', 'texte', 'lien', 'date_deb', 'date_fin')
values (5, 5, 5, NULL, 'pk 1', NULL, NULL, NULL);




php -S localhost:8000 -t public/

/* on veut récupérer les points et les icones des éléments qui appartiennent aux types correpondants à un type de calque */
/* pour le calque 'ER' */
select point.latitude, point.longitude, element.photo, element.texte, element.lien, element.date_deb, element.date_fin, type_element.nom, type_calque.nom, icone.couleur, icone.lien from type_calque
    inner join type_element on type_calque.id = type_element.type_calque_id
    inner join element on element.type_element_id = type_element.id
    inner join icone on element.icone_id = icone.id
    inner join point on point.element_id = element.id
    where type_calque.type = 'ER'