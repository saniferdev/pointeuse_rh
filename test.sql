SELECT
    SUB.dat AS 'Date' 
    ,SUB.n AS 'N°'
    ,SUB.nom AS 'Nom'
    ,SUB.pre AS 'Prénom'
    ,SUB.dep AS 'Département'
    ,SUB.soc AS 'Société'
    ,SUB.q AS 'Qualification'
    ,ISNULL(MIN(CONVERT(varchar, SUB.em, 8)),'') AS 'Entree Matin'
    ,ISNULL(MIN(CONVERT(varchar, SUB.sm, 8)),'') AS 'Sortie Matin'
    ,ISNULL(MAX(CONVERT(varchar, SUB.emm, 8)),'') AS 'Entree Après-Midi'
    ,ISNULL(MAX(CONVERT(varchar, SUB.smm, 8)),'') AS 'Sortie Après-Midi'
    ,CONCAT(
            (DATEDIFF(Minute, MIN(SUB.em), CASE WHEN MAX(SUB.em) > MAX(SUB.smm) OR MAX(SUB.smm) IS NULL THEN MAX(SUB.em) ELSE MAX(SUB.smm) END))/60
            ,':',(DATEDIFF(Minute, MIN(SUB.em), CASE WHEN MAX(SUB.em) > MAX(SUB.smm) OR MAX(SUB.smm) IS NULL THEN MAX(SUB.em) ELSE MAX(SUB.smm) END))%60) AS total
FROM (

    SELECT
        CONVERT(varchar, A.[time], 105) AS dat
        ,U.Badgenumber AS n
        ,ISNULL(U.[Name],'') AS nom
        ,ISNULL(U.lastname,'') AS pre
        ,ISNULL(LP.fonction,'') AS dep
        ,ISNULL(LP.departement,'') AS soc
        ,ISNULL(LP.titre,'') AS q
        ,CASE WHEN CAST( CONVERT(CHAR(2), A.[time], 108) AS INT) < 12 AND A.[time] IS NOT NULL THEN A.[time] END AS em
        ,CASE WHEN (CAST( CONVERT(CHAR(2), A.[time], 108) AS INT) BETWEEN 12 AND 15) AND A.device_id <> 4 AND A.[time] IS NOT NULL
                    THEN 
                        A.[time] END AS emm
        ,CASE WHEN (CAST( CONVERT(CHAR(2), A2.[time], 108) AS INT) BETWEEN 11 AND 15) AND A2.device_id <> 7 THEN A2.[time] END AS sm
        ,CASE 
            WHEN CAST( CONVERT(CHAR(2), A2.[time], 108) AS INT) > 15 THEN A2.[time]
            WHEN CAST( CONVERT(CHAR(2), A.[time], 108) AS INT) > 15 THEN A.[time]
        END AS smm
        
    FROM        
        USERINFO U 
        INNER JOIN [acc_monitor_log] A ON U.Badgenumber = A.pin AND CAST(A.[time] AS DATE) BETWEEN '2021-09-01' AND '2021-09-15' AND DATEPART(hour, A.[time]) < '20'  
        LEFT JOIN DEPARTMENTS D ON D.DEPTID = U.DEFAULTDEPTID
        LEFT JOIN ZKTIME.dbo.acc_monitor_log A2 ON A2.pin COLLATE DATABASE_DEFAULT = A.pin AND CAST(A2.[time] AS DATE) = CAST(A.[time] AS DATE)
        LEFT JOIN [ZTITRE].[dbo].[liste_personnel] LP ON CONVERT(INT, LP.num) = U.Badgenumber AND UPPER(LP.nom) COLLATE DATABASE_DEFAULT = UPPER(REPLACE(U.[Name],'.',''))
    WHERE
        U.Badgenumber <> U.[Name]
        AND U.Badgenumber NOT IN (1,2,3,4,5)
        AND U.Badgenumber = 39
        --AND LP.departement = 'S1'
    ) AS SUB
GROUP BY
    SUB.dat
    ,SUB.n
    ,SUB.nom
    ,SUB.pre
    ,SUB.dep
    ,SUB.soc
    ,SUB.q

ORDER BY
    SUB.n + 0 ASC,
    SUB.dat;