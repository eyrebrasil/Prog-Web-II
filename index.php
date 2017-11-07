<!DOCTYPE html>
<html lang="pt-br">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Site AV2">
        <meta name="author" content="Eyre Brasil">

        <title>Minha Estante Virtual</title>

        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800"
              rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic"
              rel="stylesheet" type="text/css">

        <!-- Custom styles for this template -->
        <link href="css/business-casual.css" rel="stylesheet">

    </head>

    <body>

        <div class="tagline-upper text-center text-heading text-shadow text-white mt-5 d-none d-lg-block">Minha Estante Virtual</div>
        <div class="tagline-lower text-center text-expanded text-shadow text-uppercase text-white mb-5 d-none d-lg-block">Lasalle | SI | Prog Web II</div>

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-faded py-lg-4">
            <div class="container">
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item px-lg-4">
                            <a class="nav-link text-uppercase text-expanded" href="index.php">Home
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container text-heading">

            <div class="bg-faded p-4 my-4">
                <hr class="divider">
                <h2 class="text-center text-lg text-uppercase my-0">
                    <strong>Listagem de Livros</strong>
                </h2>
                <hr class="divider">

                <form action="#" method="post">

                    <div class="row">

                        <div class="form-group col-lg-4">
                            <label class="text-heading">Título:</label>
                            <input type="text" class="form-control" name="titulo">
                        </div>
                        <div class="form-group col-lg-4">
                            <label class="text-heading">Autor:</label>
                            <input type="text" class="form-control" name="autor">
                        </div>
                        <div class="form-group col-lg-4">
                            <label class="text-heading">Tags:</label>
                            <input type="text" class="form-control" name="tag">
                        </div>

                        <div class="form-group col-lg-4">
                            <label class="text-heading">Emprestado para:</label>
                            <input type="text" class="form-control" name="emprestado">
                        </div>
                        <div class="form-group col-lg-4">
                            <label class="text-heading">Ordenar por:</label>&nbsp;&nbsp;
                            <select name="ordem" class="form-control text-heading">
                                <option value="titulo" selected>Título</option>
                                <option value="autor" selected>Autor</option>
                                <option value="dataAsc" selected>Mais recentes</option>
                                <option value="dataDes" selected>Mais antigos</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-4">
                            <label class="text-heading">Status:</label>&nbsp;&nbsp;
                            <div>
                                <input type="radio" name="status" value="" checked>
                                <label class="text-heading">Todos</label>&nbsp;
                                <input type="radio" name="status" value="1">
                                <label class="text-heading">Lido</label>&nbsp;
                                <input type="radio" name="status" value="0">
                                <label class="text-heading">Vou ler</label>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="form-group col-lg-12 text-right">
                            <button type="submit" class="btn btn-secondary" name="acao" value="listar">Pesquisar</button>
                            <button type="reset" class="btn btn-secondary">Limpar</button>
                        </div>

                    </div>

                </form>
            </div>

            <div class="bg-faded p-4 my-4">
                <form action="livro.php" method="post">
                    <div class="row">
                        <div class="clearfix"></div>
                        <div class="form-group col-lg-12 text-right">
                            <button type="submit" class="btn btn-secondary" name="acao" value="novo">Novo</button>
                        </div>
                        <div class="form-group col-lg-12 table-responsive">                           
                            <?php
                            /**
                             * Recebendo valores do form
                             */
                            $titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : "";
                            $autor = isset($_POST["autor"]) ? $_POST["autor"] : "";
                            $emprestado = isset($_POST["emprestado"]) ? $_POST["emprestado"] : "";
                            $ordem = isset($_POST["ordem"]) ? $_POST["ordem"] : "";
                            $status = isset($_POST["status"]) ? $_POST["status"] : "";
                            $tag = isset($_POST["tag"]) ? $_POST["tag"] : "";
                            $acao = isset($_POST["acao"]) ? $_POST["acao"] : "";
                            
                            switch ($ordem) {
                                case "titulo":
                                    $ordem = "TITUL_LIV";
                                    break;
                                case "autor":
                                    $ordem = "AUTOR_LIV";
                                    break;
                                case "dataAsc":
                                    $ordem = "DTLEI_LIV";
                                    break;
                                case "dataDes":
                                    $ordem = "DTLEI_LIV DESC";
                                    break;
                            }
                           
                            /**
                             * Setando conexao
                             */
                            $host = "localhost";
                            $user = "root";
                            $pwd = "";
                            $db = "progweb";
                            $conn = mysqli_connect($host, $user, $pwd, $db);
                            /**
                             * Execução do programa
                             */
                            if (!$conn) {
                                die("Connection failed: " . mysqli_connect_error());
                            } else {
                                switch ($acao) {
                                    case "listar":
                                        listar($conn, $titulo, $autor, $emprestado, $ordem, $status, $tag);
                                        break;
                                    default:
                                        listarTodos($conn);
                                        break;
                                }
                                mysqli_close($conn);
                            }

                            /**
                             * Funcoes
                             */
                            function listar($conn, $titulo, $autor, $emprestado, $ordem, $status, $tag) {
                                $query = "  SELECT DISTINCT IDLIV_LIV, TITUL_LIV, AUTOR_LIV, CASE STLEI_LIV WHEN 1 THEN 'Lido' ELSE 'Não Lido' END STLEI_LIV, CAST(DATE_FORMAT(DTLEI_LIV, '%d/%m/%Y') AS CHAR(8)) DTLEI_LIV, EMPRE_LIV
                                            FROM LIVRO, TAG WHERE IDLIV_LIV = IDLIV_TAG " .
                                            (strlen($titulo) == 0 ? "" : " AND TITUL_LIV LIKE '%$titulo%' ") .
                                            (strlen($autor) == 0 ? "" : " AND AUTOR_LIV LIKE '%$autor%' ") .                            
                                            (strlen($emprestado) == 0 ? "" : " AND EMPRE_LIV LIKE '%$emprestado%' ") .
                                            (strlen($tag) == 0 ? "" : " AND DESCR_TAG IN (" . ("'" . str_replace(",", "','", $tag) . "'") . ") ") .
                                            (strlen($status) == 0 ? "" : " AND STLEI_LIV = '$status' ") .
                                            " ORDER BY $ordem";
                              
                                $result = mysqli_query($conn, $query);
                                $count = mysqli_num_rows($result);

                                if ($count > 0) {

                                    echo "<table class='table table-hover'>
                                                    <thead class='text-lg text-uppercase '>
                                                        <tr>
                                                            <th>Título</th>
                                                            <th>Autor</th>
                                                            <th>Status</th>
                                                            <th>Lido em</th>
                                                            <th>Emprestado para</th>
                                                            <th class='text-center'>Editar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>";

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $row["TITUL_LIV"] . "</td>";
                                        echo "<td>" . $row["AUTOR_LIV"] . "</td>";
                                        echo "<td>" . $row["STLEI_LIV"] . "</td>";
                                        echo "<td>" . $row["DTLEI_LIV"] . "</td>";
                                        echo "<td>" . $row["EMPRE_LIV"] . "</td>";
                                        $value = "editar-" . $row["IDLIV_LIV"];
                                        echo "<td class='text-center'><button type='submit' name='acao' value='$value' class='button-icon'><img src='img/edit.png' class='img-fluid icon'></button></td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody></table>";
                                } else {
                                    echo "Não há registro(s) para sere(m) exibido(s)";
                                }
                            }
                            
                            function listarTodos($conn) {
                                $query = "  SELECT DISTINCT 
                                                IDLIV_LIV, TITUL_LIV, AUTOR_LIV, 
                                                CASE STLEI_LIV WHEN 1 THEN 'Lido' ELSE 'Não Lido' END STLEI_LIV, 
                                                DTLEI_LIV, EMPRE_LIV
                                            FROM LIVRO, TAG WHERE IDLIV_LIV = IDLIV_TAG                                                   
                                            ORDER BY TITUL_LIV";

                                $result = mysqli_query($conn, $query);
                                $count = mysqli_num_rows($result);

                                if ($count > 0) {

                                    echo "<table class='table table-hover'>
                                                    <thead class='text-lg text-uppercase '>
                                                        <tr>
                                                            <th>Título</th>
                                                            <th>Autor</th>
                                                            <th>Status</th>
                                                            <th>Lido em</th>
                                                            <th>Emprestado para</th>
                                                            <th class='text-center'>Editar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>";

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $row["TITUL_LIV"] . "</td>";
                                        echo "<td>" . $row["AUTOR_LIV"] . "</td>";
                                        echo "<td>" . $row["STLEI_LIV"] . "</td>";
                                        echo "<td>" . $row["DTLEI_LIV"] . "</td>";
                                        echo "<td>" . $row["EMPRE_LIV"] . "</td>";
                                        $value = "editar-" . $row["IDLIV_LIV"];
                                        echo "<td class='text-center'><button type='submit' name='acao' value='$value' class='button-icon'><img src='img/edit.png' class='img-fluid icon'></button></td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody></table>";
                                } else {
                                    echo "Não há registro(s) para sere(m) exibido(s)";
                                }
                            }
                            ?>                               
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.container -->

        <footer class="bg-faded text-center py-5">
            <div class="container">
                <p class="m-0">Copyright &copy; Eyre Brasil Montevecchi 2017</p>
            </div>
        </footer>

        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Zoom when clicked function for Google Map -->
        <script>
            $('.map-container').click(function () {
                $(this).find('iframe').addClass('clicked')
            }).mouseleave(function () {
                $(this).find('iframe').removeClass('clicked')
            });
        </script>

    </body>

</html>