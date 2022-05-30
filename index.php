<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Teste para Importação de Arquivo CSV</title>
</head>
<body>

    <main role="main">
        <div class="jumbotron">
            <div class="container">
                <h1 class="display-4">Teste Importação de Dados de Arquivo CSV!</h1>
                <p>Para testar a importação de Dados de um arquivo CSV crie um arquivo no formato CSV com os dados devidamente estruturados e separados com "ponto e vígula (;)"</p>
            </div>
        </div>

        <div class="container">
            <div class="d-flex justify-content-center">
                <form name="import-csv" method="post" class="form-signin" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="import_data"/>
                    <input type="file" name="file_csv" required/>
                    <button class="btn btn-primary btn-block mt-5 mb-3" type="submit">Importar Dados</button>
                </form>
            </div>

            <div class="row mt-4">
                <div class="col-md-12 mt-4">
                    <?php 
                      $postData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                      
                      require_once "ImportCSV.php";
                      $import = new ImportCSV();
                        if(isset($_POST['data_import'])) {
                            $passed_array = unserialize($_POST['data_import']);
                            $import->save($passed_array);
                        }
                      if($postData && $postData["action"] == "import_data"){

                          if (empty($_FILES['file_csv'])){
                              echo '<div class="alert alert-danger" role="alert"> Atenção! Envie um arquivo CSV </div>';
                              die;
                            }
                            
                            if ($_FILES['file_csv']['type'] != 'text/csv'){
                                echo '<div class="alert alert-danger" role="alert"> Atenção! O arquivo enviado parece não ser do tipo CSV </div>';
                                die;
                            }
                            
                            $input_file = $_FILES['file_csv'];
                            $extension = pathinfo($_FILES['file_csv']['name']);
                            $remove_header = true;
                            
                          $import->setInputFile($input_file, $remove_header);
                        // echo '<pre>';
                        //   print_r($import);
                        //   print_r($input_file);
                        // echo '</pre>';
                          if($import->getResult()) {
                                $data_import = $import->getResult();
                                $count = 1;
                                ?>
                                <div class="d-flex justify-content-center">
                                    <form name="import-csv" method="post" class="form-signin">
                                        <input type="hidden" name="action" value="save_data"/>
                                            <input type="hidden" name="data_import" value="<?= htmlentities(serialize($data_import)) ?>" />
                                        <input type="submit" class="btn btn-primary btn-block mt-5 mb-3" name="button1" value="Salvar Dados" />
                                        </form>
                                </div>
                                <h2 class="text-center">Dados Importados do Arquivo Texto (CSV)</h2>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">E-mail</th>
                                        <th scope="col">Profissão</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                          <?php
                                          foreach ($data_import as $value) {
                                          ?>
                                              <tr>
                                                  <th scope="row"><?= $count; ?></th>
                                                  <td><?= $value[0]; ?></td>
                                                  <td><?= $value[1]; ?></td>
                                                  <td><?= $value[2]; ?></td>
                                              </tr>
                                          <?php
                                              $count++;
                                          }
                                          ?>
                                    </tbody>
                                </table>
                                <?php
                          }
                      }

                  ?>
                </div>
            </div>
        </div>
    </main>
    <footer class="container text-center mt-4"  >
        <p>Maio 2022 - V.1.0 </p>
    </footer>
</body>
</html>
