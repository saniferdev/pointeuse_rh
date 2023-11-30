<?php
class employee {
    public $link;
    public function employees(){
        $queryParams  = $data = array();
        $queryOptions = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
        $query = "
                    SELECT 
                        Badgenumber
                        ,[Name]
                        ,ISNULL(lastname,'') AS lastname
                    FROM 
                        USERINFO ;";

        $resultat = sqlsrv_query($this->link, $query, $queryParams, $queryOptions);
        if ($resultat == FALSE) {
                var_dump(sqlsrv_errors());
            return false;
        } elseif (sqlsrv_num_rows($resultat) == 0) {
            return false;
        } else {
            while($row = sqlsrv_fetch_array($resultat, SQLSRV_FETCH_NUMERIC)){
                $data[] = $row;
            }
        }
        return $data;
    }

    public function site(){
        $queryParams  = $data = array();
        $queryOptions = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
        $query = "
                    SELECT 
                        [DEPTID]
                        ,[DEPTNAME]
                     FROM 
                        [IN].[dbo].[DEPARTMENTS] 
                    WHERE 
                        SUPDEPTID = 1 
                    ORDER BY 
                        DEPTNAME ;";

        $resultat = sqlsrv_query($this->link, $query, $queryParams, $queryOptions);
        if ($resultat == FALSE) {
                var_dump(sqlsrv_errors());
            return false;
        } elseif (sqlsrv_num_rows($resultat) == 0) {
            return false;
        } else {
            while($row = sqlsrv_fetch_array($resultat, SQLSRV_FETCH_NUMERIC)){
                $data[] = $row;
            }
        }
        return $data;
    }

    public function machines(){
        $queryParams  = $data = array();
        $queryOptions = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
        $query = "
                    SELECT 
                        ID
                        ,MachineAlias AS m
                    FROM 
                        Machines
                    ORDER BY
                        MachineAlias ;";

        $resultat = sqlsrv_query($this->link, $query, $queryParams, $queryOptions);
        if ($resultat == FALSE) {
                var_dump(sqlsrv_errors());
            return false;
        } elseif (sqlsrv_num_rows($resultat) == 0) {
            return false;
        } else {
            while($row = sqlsrv_fetch_array($resultat, SQLSRV_FETCH_NUMERIC)){
                $data[] = $row;
            }
        }
        return $data;
    }

    public function requete($date_debut,$date_fin,$num,$type,$site,$t){
        $join         = "INNER";
        $q            = $q_t = $stx = $ss = $trip = "";

        $array        = array("1"=>"13", "2"=>"5", "3"=>"10", "4"=>"9", "5"=>"11", "6"=>"7");

        if(isset($num) && !empty($num)){
            $q        .= "AND A.pin = ".$num;
        }

        if(isset($site) && !empty($site)){
            $site    = $array[$site];
            if($site == "5"){
                $q_t      .= "AND (A.device_id = 5 OR A.device_id = 6)";
            }
            else if($site == "13" || $site == "10" || $site == "9" || $site == "11"  || $site == "10"){
                $q_t      .= "AND A.device_id = ".$site;
            }
            else{
                $q_t      .= " AND (A.device_id = 7 OR A.device_id = 4) ";
            }
            
        }
        else{
            $site     = "5";
        }

        if($site == "7" || $site == "5" || $site == "13"){
            $stx   = "A2";
            $ss    = " LEFT JOIN ZKTIME.dbo.acc_monitor_log A2 ON A2.pin COLLATE DATABASE_DEFAULT = A.pin AND CAST(A2.[time] AS DATE) = CAST(A.[time] AS DATE) ";

            if($site == "5"){
                $q_t      .= "AND (A.device_id = 5 OR A.device_id = 6)";
            }
            else if($site == "13"){
                $q_t      .= "OR A.device_id = 13";
            }
            else{
                $trip     .= " OR A.device_id = 4 ";
            }
        }
        else{
            $stx = "A";
        }

        if(isset($type) && !empty($type)){
            if($type=='r'){
                $q_t      .= " AND (
                                SELECT 
                                    CAST(CONVERT(CHAR(8), MIN([time]), 108) AS DATETIME)
                                FROM 
                                    [acc_monitor_log]
                                WHERE 
                                    CAST([time] AS DATE) = CAST(A.[time] AS DATE)
                                    AND pin = A.pin
                            ) > CAST('08:10' AS DATETIME) ";
            }
            else{
                $join     = "LEFT";
                $q_t      .= " AND ( A.pin IS NULL 
                                        AND 
                                    ".$stx.".pin IS NULL ) ";
            }
            
        }

        $queryParams  = $data = array();
        $queryOptions = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
        $query = "
                    SELECT
                        SUB.dat AS 'Date' 
                        ,SUB.n AS 'N°'
                        ,REPLACE(SUB.nom,'.','') AS 'Nom'
                        ,REPLACE(SUB.pre,'.','') AS 'Prénom'
                        ,CASE WHEN SUB.dep = 'Company Name' THEN '' ELSE SUB.dep END AS 'Département'
                        ,SUB.soc AS 'Société'
                        ,SUB.q AS 'Qualification'
                        ,ISNULL(MIN(CONVERT(varchar, SUB.em, 8)),'') AS 'Entree AM'
                        ,ISNULL(MIN(CONVERT(varchar, SUB.sm, 8)),'') AS 'Sortie M'
                        ,ISNULL(MAX(CONVERT(varchar, SUB.emm, 8)),'') AS 'Entree PM'
                        ,ISNULL(MAX(CONVERT(varchar, SUB.smm, 8)),'') AS 'Sortie PM'
                        ,CONCAT(
                                (DATEDIFF(Minute, MIN(SUB.em), CASE WHEN MAX(SUB.em) > MAX(SUB.smm) OR MAX(SUB.smm) IS NULL THEN MAX(SUB.em) ELSE MAX(SUB.smm) END))/60
                                ,':',(DATEDIFF(Minute, MIN(SUB.em), CASE WHEN MAX(SUB.em) > MAX(SUB.smm) OR MAX(SUB.smm) IS NULL THEN MAX(SUB.em) ELSE MAX(SUB.smm) END))%60) AS 'Total Horaire'
                    FROM (

                        SELECT
                            CONVERT(varchar, A.[time], 105) AS dat
                            ,U.Badgenumber AS n
                            ,ISNULL(U.[Name],'') AS nom
                            ,ISNULL(U.lastname,'') AS pre
                            ,ISNULL(D.DEPTNAME,'') AS dep
                            ,ISNULL(LP.departement,'') AS soc
                            ,ISNULL(LP.titre,'') AS q
                            ,CASE 
                                WHEN CAST( CONVERT(CHAR(2), A.[time], 108) AS INT) < 12 
                                AND A.[time] IS NOT NULL ".$trip."
                                    THEN A.[time] END AS em
                            ,CASE 
                                WHEN (CAST( CONVERT(CHAR(2), A.[time], 108) AS INT) BETWEEN 12 AND 14) 
                                AND A.device_id <> 4 
                                AND A.[time] IS NOT NULL
                                    THEN A.[time] END AS emm
                            ,CASE 
                                WHEN (CAST( CONVERT(CHAR(2), ".$stx.".[time], 108) AS INT) BETWEEN 11 AND 14) 
                                AND ".$stx.".device_id <> 7
                                AND DATEPART(DW, ".$stx.".[time]) <> 6
                                    THEN ".$stx.".[time] END AS sm
                            ,CASE 
                                WHEN CAST( CONVERT(CHAR(2), ".$stx.".[time], 108) AS INT) > 15
                                    THEN ".$stx.".[time]
                                WHEN CAST( CONVERT(CHAR(2), A.[time], 108) AS INT) > 15
                                ".$trip."
                                    THEN A.[time]
                                WHEN DATEPART(DW, ".$stx.".[time]) = 6
                                    THEN ".$stx.".[time]
                            END AS smm

                        FROM
                            USERINFO U
                            $join JOIN [acc_monitor_log] A ON U.Badgenumber = A.pin AND CAST(A.[time] AS DATE) BETWEEN '$date_debut' AND '$date_fin' AND DATEPART(hour, A.[time]) < '20' $q 
                            LEFT JOIN DEPARTMENTS D ON D.DEPTID = U.DEFAULTDEPTID
                            $ss
                            LEFT JOIN [ZTITRE].[dbo].[liste_personnel] LP ON CONVERT(INT, LP.num) = U.Badgenumber AND LP.departement NOT IN ('CINEPAX','TBH','TS') AND UPPER(LP.nom) COLLATE DATABASE_DEFAULT = UPPER(REPLACE(U.[Name],'.',''))

                        WHERE
                            U.Badgenumber <> U.[Name]
                            AND U.Badgenumber NOT IN (1,2,3,4,5)
                            $q_t

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
                        SUB.dat;";
        //echo $query;
        $resultat = sqlsrv_query($this->link, $query, $queryParams, $queryOptions);
        if ($resultat == FALSE) {
                var_dump(sqlsrv_errors());
            return false;
        } elseif (sqlsrv_num_rows($resultat) == 0) {
            return false;
        } else {
            while($row = sqlsrv_fetch_array($resultat, $t)){
                $data[] = $row;
            }
        }
        return $data;
    }

    public function conversion($date){
        $myDateTime     = DateTime::createFromFormat('d/m/Y', $date);
        $newDateString  = $myDateTime->format('Y-m-d');
        return $newDateString;
    }

    public function nombre_G($nombre){
        if(strlen($nombre) == 1) 
            $nombre = str_pad($nombre, 2, '0', STR_PAD_LEFT); 
        return $nombre;
    }

    public function nombre_D($nombre){
        if(strlen($nombre) == 1) 
            $nombre = str_pad($nombre, 2, '0', STR_PAD_RIGHT);
        return $nombre;
    }

}
