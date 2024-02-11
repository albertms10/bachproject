USE `bachproject`;

CREATE TABLE IF NOT EXISTS `compositors` (
  `id_compositor` int(4) NOT NULL AUTO_INCREMENT,
  `nom_compositor` varchar(50) NOT NULL,
  `cognom_compositor` varchar(50) NOT NULL,
  `naixement` date,
  `defunció` date,
  `id_cataleg` int(4),
  PRIMARY KEY (`id_compositor`),
  FOREIGN KEY (`id_cataleg`) REFERENCES `catalegs`(`id_cataleg`)
)

CREATE TABLE IF NOT EXISTS `obres_compositors` (
  `id_obra_compositor` int(4) NOT NULL AUTO_INCREMENT,
  `id_obra` int(4) NOT NULL,
  `id_compositor` int(4) NOT NULL,
  `relacio` enum('G', 'D', 'F'),
  PRIMARY KEY (`id_obra_compositor`),
  FOREIGN KEY (`id_obra`) REFERENCES `obres`(`id_obra`),
  FOREIGN KEY (`id_compositor`) REFERENCES `compositors`(`id_compositor`)
)

CREATE TABLE IF NOT EXISTS `catalegs` (
  `id_cataleg` int(4) NOT NULL AUTO_INCREMENT,
  `inicials_cataleg` varchar(10) NOT NULL,
  `nom_cataleg` varchar(50) NOT NULL,
  PRIMARY KEY (`id_cataleg`)
)

CREATE TABLE IF NOT EXISTS `generes` (
  `id_genere` int(4) NOT NULL AUTO_INCREMENT,
  `titol_genere` varchar(50) NOT NULL,
  PRIMARY KEY (`id_genere`)
)

CREATE TABLE IF NOT EXISTS `subgeneres` (
  `id_subgenere` int(4) NOT NULL AUTO_INCREMENT,
  `titol_subgenere` varchar(50) NOT NULL,
  `id_genere` int(4) NOT NULL,
  PRIMARY KEY (`id_subgenere`),
  FOREIGN KEY (`id_genere`) REFERENCES `generes`(`id_genere`)
)

CREATE TABLE IF NOT EXISTS `atributs` (
  `id_atribut` int(4) NOT NULL AUTO_INCREMENT,
  `titol_atribut` varchar(50) NOT NULL,
  `id_subgenere` int(4),
  PRIMARY KEY (`id_atribut`),
  FOREIGN KEY (`id_subgenere`) REFERENCES `subgeneres`(`id_subgenere`)
)

CREATE TABLE IF NOT EXISTS `obres` (
  `id_obra` int(4) NOT NULL AUTO_INCREMENT,
  `titol_obra` varchar(50) NOT NULL,
  `num_cataleg` int(4),
  `subnum_cataleg` int(2),
  `data_composicio` date,
  `id_genere` int(4),
  PRIMARY KEY (`id_obra`),
  FOREIGN KEY (`id_genere`) REFERENCES `generes`(`id_genere`)
)

CREATE TABLE IF NOT EXISTS `obres_catalegs` (
  `id_obra_cataleg` int(6) NOT NULL AUTO_INCREMENT,
  `num_cataleg` int(5) NOT NULL,
  `id_obra` int(4) NOT NULL,
  `id_cataleg_compositor` int(4) NOT NULL,
  PRIMARY KEY (`id_obra_cataleg`),
  FOREIGN KEY (`id_obra`) REFERENCES `obres`(`id_obra`),
  FOREIGN KEY (`id_cataleg_compositor`) REFERENCES `catalegs_compositors`(`id_cataleg_compositor`)
)

CREATE TABLE IF NOT EXISTS `obres_anys` (
  `id_obra_any` int(6) NOT NULL AUTO_INCREMENT,
  `any_inici` int(4) NOT NULL,
  `any_final` int(4) NOT NULL,
  `is_revisio` int(1) DEFAULT '0',
  `is_dubtos` int(1) DEFAULT '0',
  `is_circa` int(1) DEFAULT '0',
  `id_obra` int(4) NOT NULL,
  PRIMARY KEY (`id_obra_any`),
  FOREIGN KEY (`id_obra`) REFERENCES `obres`(`id_obra`)
)

CREATE TABLE IF NOT EXISTS `submoviments` (
  `id_submoviment` int(4) NOT NULL AUTO_INCREMENT,
  `num_submoviment` int(4) NOT NULL,
  `titol_submoviment` varchar(100),
  `id_moviment` int(6) NOT NULL,
  PRIMARY KEY (`id_submoviment`),
  FOREIGN KEY (`id_moviment`) REFERENCES `moviments`(`id_moviment`)
)

CREATE TABLE IF NOT EXISTS `moviments` (
  `id_moviment` int(6) NOT NULL AUTO_INCREMENT,
  `num_moviment` int(4) NOT NULL,
  `titol_moviment` varchar(100),
  `id_obra` int(4) NOT NULL,
  `id_seccio` int(4),
  PRIMARY KEY (`id_moviment`),
  FOREIGN KEY (`id_obra`) REFERENCES `obres`(`id_obra`),
  FOREIGN KEY (`id_seccio`) REFERENCES `seccions`(`id_seccio`)
)

CREATE TABLE IF NOT EXISTS `seccions` (
  `id_seccio` int(4) NOT NULL AUTO_INCREMENT,
  `num_seccio` int(4) NOT NULL,
  `titol_seccio` varchar(100),
  `id_capitol` int(4),
  PRIMARY KEY (`id_seccio`),
  FOREIGN KEY (`id_capitol`) REFERENCES `capitols`(`id_capitol`)
)

CREATE TABLE IF NOT EXISTS `capitols` (
  `id_capitol` int(4) NOT NULL AUTO_INCREMENT,
  `num_capitol` int(4) NOT NULL,
  `titol_capitol` varchar(100),
  PRIMARY KEY (`id_capitol`)
)

CREATE TABLE IF NOT EXISTS `aparicions` (
  `id_aparicio` int(4) NOT NULL AUTO_INCREMENT,
  `compas_inici` int(4),
  `compas_final` int(4),
  `temps` int(1),
  `veu` enum('S', 'A', 'T', 'B'),
  `tipus` enum('BACH', 'Numerologia'),
  `comentaris` text,
  `id_obra` int(4) NOT NULL,
  `id_moviment` int(6) NOT NULL,
  `id_usuari` int(4) NOT NULL,
  `timestamp` timestamp,
  PRIMARY KEY (`id_aparicio`),
  FOREIGN KEY (`id_obra`) REFERENCES `obres`(`id_obra`),
  FOREIGN KEY (`id_moviment`) REFERENCES `moviments`(`id_moviment`),
  FOREIGN KEY (`id_usuari`) REFERENCES `usuaris_protected`(`id_usuari`)
)

CREATE TABLE IF NOT EXISTS `t_semitons` (
  `id_semito` int(2) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_semito`)
)

CREATE TABLE IF NOT EXISTS `t_modes` (
  `id_mode` varchar(1) NOT NULL,
  PRIMARY KEY (`id_mode`)
)

CREATE TABLE IF NOT EXISTS `t_notes` (
  `id_nota` int(2) NOT NULL AUTO_INCREMENT,
  `nom_nota` varchar(3) NOT NULL,
  PRIMARY KEY (`id_nota`)
)

CREATE TABLE IF NOT EXISTS `t_alteracions` (
  `id_alteracio` varchar(1) NOT NULL,
  `nom_alteracio` varchar(10) NOT NULL,
  PRIMARY KEY (`id_alteracio`)
)

CREATE TABLE IF NOT EXISTS `t_cromatiques` (
  `id_cromatica` int(2) NOT NULL AUTO_INCREMENT,
  `id_nota` int(2) NOT NULL,
  `id_alteracio` varchar(1) NOT NULL,
  PRIMARY KEY (`id_cromatica`),
  FOREIGN KEY (`id_nota`) REFERENCES `t_notes`(`id_nota`),
  FOREIGN KEY (`id_alteracio`) REFERENCES `t_alteracions`(`id_alteracio`)
)

CREATE TABLE IF NOT EXISTS `t_tonalitats` (
  `id_tonalitat` int(2) NOT NULL AUTO_INCREMENT,
  `id_semito` int(2) NOT NULL,
  `id_mode` varchar(1) NOT NULL,
  `id_nota` int(2) NOT NULL,
  PRIMARY KEY (`id_tonalitat`),
  FOREIGN KEY (`id_semito`) REFERENCES `t_semitons`(`id_semito`),
  FOREIGN KEY (`id_mode`) REFERENCES `t_modes`(`id_mode`)
  FOREIGN KEY (`id_nota`) REFERENCES `t_notes`(`id_nota`)
)

CREATE TABLE IF NOT EXISTS `usuaris` (
  `id_usuari` int(4) NOT NULL AUTO_INCREMENT,
  `nom_usuari` varchar(25) NOT NULL,
  `cognom_usuari` varchar(25),
  `email` varchar(50),
  `contrasenya` varchar(255) NOT NULL,
  PRIMARY KEY (`id_usuari`)
)

CREATE TABLE IF NOT EXISTS `obres_relacions` (
  `id_obra_relacio` int(4) NOT NULL AUTO_INCREMENT,
  `id_obra_1` int(4) NOT NULL,
  `relacio` enum('A', 'B', 'R', 'U', 'V') NOT NULL,
  `is_parcialment` int(1) DEFAULT '0',
  `id_obra_2` int(4) NOT NULL,
  PRIMARY KEY (`id_obra_relacio`),
  FOREIGN KEY (`id_obra_1`) REFERENCES `obres`(`id_obra`),
  FOREIGN KEY (`id_obra_2`) REFERENCES `obres`(`id_obra`)
)

CREATE TABLE IF NOT EXISTS `llibres` (
  `id_llibre` int(4) NOT NULL AUTO_INCREMENT,
  `titol_llibre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_llibre`)
)

CREATE TABLE IF NOT EXISTS `volums` (
  `id_volum` int(4) NOT NULL AUTO_INCREMENT,
  `num_volum` int(2),
  `id_llibre` int(4) NOT NULL,
  PRIMARY KEY (`id_volum`),
  FOREIGN KEY (`id_llibre`) REFERENCES `llibres`(`id_llibre`)
)

CREATE TABLE IF NOT EXISTS `obres_volums` (
  `id_obra_volum` int(4) NOT NULL AUTO_INCREMENT,
  `id_obra` int(4) NOT NULL,
  `id_volum` int(4) NOT NULL,
  PRIMARY KEY (`id_obra_volum`),
  FOREIGN KEY (`id_obra`) REFERENCES `obres`(`id_obra`),
  FOREIGN KEY (`id_volum`) REFERENCES `volums`(`id_volum`)
)

CREATE TABLE IF NOT EXISTS `obres_favorites` (
  `id_obra_favorita` int(4) NOT NULL AUTO_INCREMENT,
  `id_obra` int(4) NOT NULL,
  `id_usuari` int(4) NOT NULL,
  `is_favorita` int(1) NOT NULL,
  PRIMARY KEY (`id_obra_favorita`),
  FOREIGN KEY (`id_obra`) REFERENCES `obres`(`id_obra`),
  FOREIGN KEY (`id_usuari`) REFERENCES `usuaris_protected`(`id_usuari`)
)

CREATE TABLE IF NOT EXISTS `instruments` (
  `id_instrument` int(4) NOT NULL AUTO_INCREMENT,
  `nom_instrument` varchar(50) NOT NULL,
  PRIMARY KEY (`id_instrument`)
)

CREATE TABLE IF NOT EXISTS `agrupacions_instruments` (
  `id_agrupacio_instrument` int(4) NOT NULL AUTO_INCREMENT,
  `id_agrupacio` int(4) NOT NULL,
  `id_instrument` int(4) NOT NULL,
  PRIMARY KEY (`id_agrupacio_instrument`),
  FOREIGN KEY (`id_agrupacio`) REFERENCES `agrupacions`(`id_agrupacio`),
  FOREIGN KEY (`id_instrument`) REFERENCES `instruments`(`id_instrument`)
)

CREATE TABLE IF NOT EXISTS `agrupacions` (
  `id_agrupacio` int(4) NOT NULL AUTO_INCREMENT,
  `nom_agrupacio` varchar(50) NOT NULL,
  PRIMARY KEY (`id_agrupacio`)
)

CREATE TABLE IF NOT EXISTS `moviments_agrupacions` (
  `id_moviment_agrupacio` int(4) NOT NULL AUTO_INCREMENT,
  `id_moviment` int(6) NOT NULL,
  `id_agrupacio` int(4) NOT NULL,
  PRIMARY KEY (`id_moviment_agrupacio`),
  FOREIGN KEY (`id_moviment`) REFERENCES `moviments`(`id_moviment`),
  FOREIGN KEY (`id_agrupacio`) REFERENCES `agrupacions`(`id_agrupacio`)
)

CREATE TABLE IF NOT EXISTS `catalegs_compositors` (
  `id_cataleg_compositor` int(4) NOT NULL AUTO_INCREMENT,
  `id_cataleg` int(4) NOT NULL,
  `id_compositor` int(4) NOT NULL,
  `default` tinyint(1),
  PRIMARY KEY (`id_cataleg_compositor`),
  FOREIGN KEY (`id_cataleg`) REFERENCES `catalegs`(`id_cataleg`),
  FOREIGN KEY (`id_compositor`) REFERENCES `compositors`(`id_compositor`)
)

CREATE TABLE IF NOT EXISTS `tipus_autories` (
  `id_tipus_autoria` tinyint(1) NOT NULL AUTO_INCREMENT,
  `tipus_autoria` varchar(50) NOT NULL,
  PRIMARY KEY (`id_tipus_autoria`)
)

CREATE TABLE IF NOT EXISTS `tipus_relacions_obres` (
  `id_tipus_relacio_obra` tinyint(1) NOT NULL AUTO_INCREMENT,
  `tipus_relacio_obra` varchar(50) NOT NULL,
  PRIMARY KEY (`id_tipus_relacio_obra`)
)

CREATE TABLE IF NOT EXISTS `tipus_relacions_compositors` (
  `id_tipus_relacio_compositor` tinyint(1) NOT NULL AUTO_INCREMENT,
  `tipus_relacio_compositor` varchar(50) NOT NULL,
  PRIMARY KEY (`id_tipus_relacio_compositor`)
)

CREATE TABLE IF NOT EXISTS `obres_subgeneres` (
  `id_obra_subgenere` int(6) NOT NULL AUTO_INCREMENT,
  `id_obra` int(4) NOT NULL,
  `id_subgenere` int(4) NOT NULL,
  PRIMARY KEY (`id_obra_subgenere`),
  FOREIGN KEY (`id_obra`) REFERENCES `obres`(`id_obra`),
  FOREIGN KEY (`id_subgenere`) REFERENCES `subgeneres`(`id_subgenere`)
)

CREATE TABLE IF NOT EXISTS `aparicions_usuaris` (
  `id_aparicio` int(4) NOT NULL,
  `id_usuari` int(4) NOT NULL,
  `timestamp` timestamp,
  PRIMARY KEY (`id_aparicio`, `id_usuari`),
  FOREIGN KEY (`id_aparicio`) REFERENCES `aparicions`(`id_aparicio`),
  FOREIGN KEY (`id_usuari`) REFERENCES `usuaris_protected`(`id_usuari`)
)

CREATE TABLE IF NOT EXISTS `temes` (
  `id_tema` int(4) NOT NULL AUTO_INCREMENT,
  `descripcio_tema` varchar(50) NOT NULL,
  PRIMARY KEY (`id_tema`)
)

CREATE TABLE IF NOT EXISTS `temes_cromatiques` (
  `id_tema_cromatica` int(4) NOT NULL AUTO_INCREMENT,
  `id_tema` int(4) NOT NULL,
  `id_cromatica` int(4) NOT NULL,
  PRIMARY KEY (`id_tema_cromatica`),
  FOREIGN KEY (`id_tema`) REFERENCES `bp_temes`(`id_tema`),
  FOREIGN KEY (`id_cromatica`) REFERENCES `bp_t_cromatiques`(`id_cromatica`)
)

CREATE TABLE IF NOT EXISTS `bp_tipus_moviments` (
  `id_tipus_moviment` int(4) NOT NULL AUTO_INCREMENT,
  `titol_tipus_moviment` varchar(50) NOT NULL,
  PRIMARY KEY (`id_tipus_moviment`)
)







-- QUERIES

SELECT CONCAT(IFNULL(prefix_cataleg, ''), num_cataleg, IFNULL(separador, ''), IFNULL(sufix_cataleg, '')) AS BC, titol_obra
FROM obres
  LEFT JOIN obres_catalegs ON obres_catalegs.id_obra = obres.id_obra
  LEFT JOIN catalegs ON obres_catalegs.id_cataleg = catalegs.id_cataleg
WHERE catalegs.id_cataleg = 2
ORDER BY prefix_cataleg, num_cataleg, sufix_cataleg


INSERT INTO compositors (nom_compositor, cognom_compositor, naixement, defuncio)
VALUES ('Johann Georg', 'Albrechtsberger', '1736-02-03', '1809-03-07')


SELECT id_semito, ANY_VALUE(cromatica_de) AS cromatica_de
FROM bp_t_cromatiques
WHERE id_semito IN (
  SELECT CASE WHEN MOD(id_semito + :t, 12) <= 0 THEN MOD(id_semito + :t, 12) + 12 ELSE MOD(id_semito + :t, 12) END
  FROM bp_temes_cromatiques
  INNER JOIN bp_temes ON bp_temes_cromatiques.id_tema = bp_temes.id_tema
  INNER JOIN bp_t_cromatiques ON bp_temes_cromatiques.id_cromatica = bp_t_cromatiques.id_cromatica
  WHERE bp_temes.id_tema = :i
)
GROUP BY id_semito
ORDER BY NULL;


/* Moviments */

SELECT JSON_ARRAYAGG(
  JSON_OBJECT(
    'id_capitol', id_capitol,
    'titol_capitol', titol_capitol,
    'seccions', seccions
  )
) AS capitols
FROM (
  SELECT JSON_ARRAYAGG(
    JSON_OBJECT(
      'id_seccio', id_seccio,
      'titol_seccio', titol_seccio,
      'moviments', moviments
    )
  ) AS seccions,
    id_capitol, titol_capitol
    FROM (
      SELECT
        IFNULL(bp_capitols.id_capitol, 'C') AS id_capitol,
        IFNULL(bp_seccions.id_seccio, 'S') AS id_seccio,
        titol_capitol,
        titol_seccio,
        JSON_ARRAYAGG(
          JSON_OBJECT(
            'id_moviment', bp_moviments.id_moviment,
            'titol_moviment', titol_moviment,
            'subtitol_moviment', subtitol_moviment
          )
        ) AS moviments
      FROM bp_moviments
        INNER JOIN bp_obres ON bp_obres.id_obra = bp_moviments.id_obra
        LEFT JOIN bp_seccions ON bp_seccions.id_seccio = bp_moviments.id_seccio
        LEFT JOIN bp_capitols ON bp_capitols.id_capitol = bp_seccions.id_capitol
      WHERE bp_obres.id_obra = :i
      GROUP BY bp_seccions.id_seccio
    ) s
    GROUP BY id_capitol, titol_capitol
) c


/* Moviments i submoviments */

SELECT JSON_ARRAYAGG(
  JSON_OBJECT(
    'id_capitol', id_capitol,
    'titol_capitol', titol_capitol,
    'seccions', seccions
  )
) AS capitols
FROM (
  SELECT JSON_ARRAYAGG(
    JSON_OBJECT(
      'id_seccio', id_seccio,
      'titol_seccio', titol_seccio,
      'moviments', moviments
    )
  ) AS seccions,
    id_capitol,
    titol_capitol
    FROM (
      SELECT
        id_capitol,
        id_seccio,
        titol_capitol,
        titol_seccio,
        JSON_ARRAYAGG(
          JSON_OBJECT(
            'id_moviment', id_moviment,
            'titol_moviment', titol_moviment,
            'subtitol_moviment', subtitol_moviment,
            'submoviments', submoviments
          )
        ) AS moviments
      FROM (
        SELECT
          IFNULL(bp_capitols.id_capitol, 'C') AS id_capitol,
          IFNULL(bp_seccions.id_seccio, 'S') AS id_seccio,
          bp_moviments.id_moviment,
     	  titol_capitol,
          titol_seccio,
          titol_moviment, subtitol_moviment,
          JSON_ARRAYAGG(
            JSON_OBJECT(
              'id_submoviment', bp_submoviments.id_submoviment,
              'titol_submoviment', titol_submoviment
            )
          ) AS submoviments
        FROM bp_moviments
          INNER JOIN bp_obres ON bp_obres.id_obra = bp_moviments.id_obra
          LEFT JOIN bp_submoviments ON bp_submoviments.id_moviment = bp_moviments.id_moviment
          LEFT JOIN bp_seccions ON bp_seccions.id_seccio = bp_moviments.id_seccio
          LEFT JOIN bp_capitols ON bp_capitols.id_capitol = bp_seccions.id_capitol
        WHERE bp_obres.id_obra = :i
        GROUP BY id_capitol, titol_capitol, id_seccio, bp_moviments.id_moviment
      ) m
      GROUP BY id_capitol, titol_capitol, id_seccio, titol_seccio
    ) s
    GROUP BY id_capitol, titol_capitol
) c
