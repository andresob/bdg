<h2>Como utilizar o site</h2>
<p>
    O site está dividido em três regiões de interesse:
    <ol>
        <li><a href="#jres">Trabalho</a></li>
        <li><a href="#jcon">Consultas</a></li>
        <li><a href="#jhis">Histórico</a></li>
    </ol>

    Dados extras
    <ul>
        <li><a href="#debaixe">Baixe o código deste site</a></li>
        <li><a href="#desobre">Sobre os autores</a></li>
    </ul>
</p>

<div id="tutorial-options">
    <h3 id="jres">Janela de Trabalho</h3>
    <p>A janela de trabalho é a área principal onde são mostradas as seguintes abas:
    <ol>
        <li><a href="#rres">Resultados</a></li>
        <?php if(geografico || showall){ ?><li><a href="#rmap">Mapa</a></li><?php }?>
        <li><a href="#rsch">Schema</a></li>
        <li><a href="#rtut">Tutorial</a></li>
    </ol>
    </p>

    <div class="janela">
        <h3 id="jcon">Janela de consultas</h3>
        <p>A janela de consultas permite preencher o formulário e executar a query no banco de dados</p>
        <p>Fica localizada na parte inferior da página (onde tem o campo de texto e o botão consultar)</p>
    </div>
    <div class="janela">
        <h3 id="jhis">Janela de histórico</h3>
        <p>Esta janela fornece as seguintes opções ao usuário:
            <ol>
                <li><a href="#hlin">Gerar link</a></li>
                <?php if(geografico || showall){ ?><li><a href="#hmer">Merge</a></li><?php }?>
                <li><a href="#hcon">Histórico de consultas</a></li>
            </ol>
        </p>
    </div>
    <div id="conteudo-tuto">

        <div class="conteudo-item">
            <h4 id="rres">Resultados</h4>
            <p>
                Ao fazer uma consulta os resultados são mostrados nesta aba.
                É gerada uma tabela a partir do resultado da consulta, mas
                caso a consulta retorne algum erro, este é exibido no lugar da tabela.
            </p>
            <?php if(geografico || showall) { ?>
            <p>
                Para os bancos de dados geográficos a tabela não exibirá a coluna geográfica exceto
                no caso em que a consulta se referir explicitamente a esta coluna. Ou seja, em uma consulta do
                tipo <pre>Select * from estado</pre> retornará todas as colunas menos a geométria dele.
            </p>
            <?php }?>
        </div>

        <?php if(geografico || showall) { ?>
        <div class="conteudo-item">
            <h4 id="rmap">Mapa</h4>
            <p>
                Nos bancos de dados geográficos, se uma consulta contiver a coluna geométrica, então será desenhado o
                mapa contendo o resultado da consulta. As duas consultas a seguir desenhariam um mapa:
                <pre>Select * from estado</pre>
                <pre>Select sigla from estado</pre>
                Isto ocorre pois a tabela estado possui coluna geométrica.
                Mas a próxima consulta não retorna um mapa, pois não possui coluna geométrica.
                <pre>select * from populacao</pre>
            </p>
        </div>
        <?php }?>
        
        <div class="conteudo-item">
            <h4 id="rsch">Schema</h4>
            <p>Uma das maiores dificuldades dos alunos ao acessar uma plataforma de consultas
            é saber qual o esquema de do banco de dados. Para resolver tal problema a aba
            schema mostra todas as tabelas do banco que podem ser consultadas, assim como suas colunas.
            As coluna sublinhadas são sempre chaves primárias. As chaves estrangeiras não
            são exibidas até o momento</p>
        </div>

        <div class="conteudo-item">
            <h4 id="hlin">Gerar link</h4>
            <p>Esta opção fica localizada no menu da direita, com o símbolo de duas correntes encadeadas (link)
            E permite gerar o link das consultas realizadas. Este link permite que sejam recuperadas as consultas
            para estudo posterior, desta forma é possível que os alunos troquem links entre si em atividades colaborativas,
            ou ainda permite que o professor passe uma lista de exercícios para a classe e peça a resposta em forma de link.</p>
        </div>

        <?php if(geografico || showall) { ?>
        <div class="conteudo-item">
            <h4 id="hmer">Merge (Unir mapas) </h4>
            <p>Esta opção fica localizada no menu da direita, com o símbolo de quadrados paralelos. E permite
            gerar um arquivo svg usando a sobreposição das consultas selecionadas. Para selecionar uma consulta clique
            na parte azul da janela de consulta. Ela mudará de cor para indicar que foi selecionada. Selecione uma ou
            mais consultas e depois clique em Merge. Você será redirecionado para uma página que contém os mapas da consulta
            disposto em camadas (uma camada por consulta). A ordem das camadas é definida pela ordem das consultas na tela.
            Ao clicar e arrastar uma consulta é possível trocá-la de lugar com outra de maneira bem simples. As cores do mapa
            a ser exibido são as mesmas definias na janela de consulta.</p>
        </div>
        <?php } ?>
        
        <div class="conteudo-item">
            <h4 id="hcon">Histórico de consultas</h4>
            <p>O histórico de consultas permite gerenciar todas as consultas que foram realizadas pelo usuário
            do site em uma mesma sessão. Todos os dados são salvos na seção do navegador de modo que algumas
            consultas do tipo <pre>select * from tabela</pre> podem deixar o navegador mais lento ou simplesmente
            ultrapassar o limite de dados suportados pelo php em uma mesma página ou em uma seção. Por isto
            este tipo de consulta é desencorajada pelos desenvolvedores do site.</p>
            <p>Para cada consulta é exibida um bloco. Estes blocos podem ser mudados de posição para facilitar a visualização
                do aluno. <?php if(geografico || showall) { ?> Nos bancos geográficos a troca de posições é importante
                pois ela define a ordem que as camadas de mapas serão geradas e consequentemente quais os mapas irão sobrepor
                os demais. <?php }?></p>
            <p>Para cada consulta as seguintes opções estão disponíveis</p>
            <ul>
                <li>Editar consulta (ícone representado pelo lápis)</li>
                <li>Apagar consulta (ícone representado pelo X)</li>

                <?php if(geografico || showall) { ?>
                <li>Modificar a cor das linhas de um mapa</li>
                <li>Modificar a cor do preenchimento de um mapa</li>
                <?php }?>
            </ul>
        </div>

        <div class="conteudo-item">
            <h4 id="rtut">Tutorial</h4>
            <p>É a aba onde este texto é exibido. Sua função? Explicar o funcionamento do site!!</p>
        </div>

    </div>
    <div id="extra">
        <div class="conteudo-item">
            <h4 id="desobre">Sobre</h4>
            <p>Desenvolvido por Thompson Moreira Filgueiras e Anderson (...) alunos da disciplina de
            Banco de dados geográficos ministrada no Departamento de Ciência da Computadação da
            Universidade Federal de Minas Gerais.</p>
            <p>Este trabalho foi desenvolvido como colaboração com o professor Clodoveu Davis
                e para os próximos alunos da disciplina </p>
        </div>

        <div class="conteudo-item">
            <h4 id="debaixe">Faça o download desta página</h4>
            <p>Este site é código aberto, sobre a licença GNU v2 e pode ser obtido atravéz do
            repositório do google code: 
            <a href="http://code.google.com/p/bdg-thom/source/checkout" target="__blank">http://code.google.com/p/bdg-thom/source/checkout</a> </p>
        </div>
    </div>
</div>