<?php
include_once('../config/conexao.php');
include_once('../includes/header.php');

if (!isset($conect)) {
    die("Erro na conexão com o banco de dados.");
}

function fetchUsers($pdo) {
    return $pdo->query("SELECT * FROM tb_user")->fetchAll(PDO::FETCH_ASSOC);
}

function fetchTopics($pdo) {
    return $pdo->query("SELECT * FROM topico")->fetchAll(PDO::FETCH_ASSOC);
}

function fetchPosts($pdo) {
    return $pdo->query("SELECT p.*, t.nome AS topic_name FROM post p JOIN topico t ON p.id_topico = t.id_topico")->fetchAll(PDO::FETCH_ASSOC);
}

// Deletar Usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $id_user = $_POST['id_user'];
    $stmt = $conect->prepare("DELETE FROM tb_user WHERE id_user = ?");
    $stmt->execute([$id_user]);
}

// Deletar Tópico
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_topic'])) {
    $id_topico = $_POST['id_topico'];
    $stmt = $conect->prepare("DELETE FROM topico WHERE id_topico = ?");
    $stmt->execute([$id_topico]);
}

// Deletar Post
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_post'])) {
    $id_post = $_POST['id_post'];
    $stmt = $conect->prepare("DELETE FROM post WHERE id_post = ?");
    $stmt->execute([$id_post]);
}

// Editar Usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
    $id_user = $_POST['id_user'];
    $nome_user = $_POST['nome_user'];
    $email_user = $_POST['email_user'];
    $stmt = $conect->prepare("UPDATE tb_user SET nome_user = ?, email_user = ? WHERE id_user = ?");
    $stmt->execute([$nome_user, $email_user, $id_user]);
}

// Editar Tópico
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_topic'])) {
    $id_topico = $_POST['id_topico'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $stmt = $conect->prepare("UPDATE topico SET nome = ?, descricao = ? WHERE id_topico = ?");
    $stmt->execute([$nome, $descricao, $id_topico]);
}

// Editar Post
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_post'])) {
    $id_post = $_POST['id_post'];
    $titulo = $_POST['titulo'];
    $corpo = $_POST['corpo'];
    $stmt = $conect->prepare("UPDATE post SET titulo = ?, corpo = ? WHERE id_post = ?");
    $stmt->execute([$titulo, $corpo, $id_post]);
}

$users = fetchUsers($conect);
$topics = fetchTopics($conect);
$posts = fetchPosts($conect);
?>

<style>
    /* Estilos para o modal */
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1; 
        left: 0; 
        top: 0; 
        width: 100%; 
        height: 100%; 
        overflow: auto; 
        background-color: rgba(0, 0, 0, 0.4); 
    }

    .modal-content {
        background-color: #fefefe; 
        margin: 15% auto; 
        padding: 20px; 
        border: 1px solid #888; 
        width: 80%; 
    }

    .close {
        color: #aaa; 
        float: right; 
        font-size: 28px; 
        font-weight: bold; 
    }

    .close:hover,
    .close:focus {
        color: black; 
        text-decoration: none; 
        cursor: pointer; 
    }
</style>
</head>

<body>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Painel do Administrador</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <h2>Gerenciar Usuários</h2>
                        <ul>
                            <?php foreach ($users as $user): ?>
                                <li>
                                    <span><?= htmlspecialchars($user['nome_user']) ?> (<?= htmlspecialchars($user['email_user']) ?>)</span>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
                                        <button type="submit" name="delete_user" onclick="return confirm('Tem certeza que deseja deletar?');">Deletar</button>
                                    </form>
                                    <button onclick="openModal('editUserModal', [
                                        { id: 'editUserId', value: '<?= $user['id_user'] ?>' },
                                        { id: 'editUserName', value: '<?= htmlspecialchars($user['nome_user']) ?>' },
                                        { id: 'editUserEmail', value: '<?= htmlspecialchars($user['email_user']) ?>' }
                                    ])">Editar</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <h2>Gerenciar Tópicos</h2>
                        <ul>
                            <?php foreach ($topics as $topic): ?>
                                <li>
                                    <span><?= htmlspecialchars($topic['nome']) ?> - <?= htmlspecialchars($topic['descricao']) ?></span>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_topico" value="<?= $topic['id_topico'] ?>">
                                        <button type="submit" name="delete_topic" onclick="return confirm('Tem certeza que deseja deletar?');">Deletar</button>
                                    </form>
                                    <button onclick="openModal('editTopicModal', [
                                        { id: 'editTopicId', value: '<?= $topic['id_topico'] ?>' },
                                        { id: 'editTopicName', value: '<?= htmlspecialchars($topic['nome']) ?>' },
                                        { id: 'editTopicDescription', value: '<?= htmlspecialchars($topic['descricao']) ?>' }
                                    ])">Editar</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <h2>Gerenciar Posts</h2>
                        <ul>
                            <?php foreach ($posts as $post): ?>
                                <li>
                                    <span><?= htmlspecialchars($post['titulo']) ?> - <?= htmlspecialchars($post['corpo']) ?> (Tópico: <?= htmlspecialchars($post['topic_name']) ?>)</span>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_post" value="<?= $post['id_post'] ?>">
                                        <button type="submit" name="delete_post" onclick="return confirm('Tem certeza que deseja deletar?');">Deletar</button>
                                    </form>
                                    <button onclick="openModal('editPostModal', [
                                        { id: 'editPostId', value: '<?= $post['id_post'] ?>' },
                                        { id: 'editPostTitle', value: '<?= htmlspecialchars($post['titulo']) ?>' },
                                        { id: 'editPostBody', value: '<?= htmlspecialchars($post['corpo']) ?>' }
                                    ])">Editar</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modais de Edição -->
<!-- Modal de Edição de Usuário -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editUserModal')">&times;</span>
        <h2>Editar Usuário</h2>
        <form id="editUserForm" method="POST">
            <input type="hidden" name="id_user" id="editUserId" value="">
            <label>Nome:</label>
            <input type="text" name="nome_user" id="editUserName" required>
            <label>Email:</label>
            <input type="email" name="email_user" id="editUserEmail" required>
            <button type="submit" name="edit_user">Salvar</button>
        </form>
    </div>
</div>

<!-- Modal de Edição de Tópico -->
<div id="editTopicModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editTopicModal')
">&times;</span>
        <h2>Editar Tópico</h2>
        <form id="editTopicForm" method="POST">
            <input type="hidden" name="id_topico" id="editTopicId" value="">
            <label>Nome:</label>
            <input type="text" name="nome" id="editTopicName" required>
            <label>Descrição:</label>
            <textarea name="descricao" id="editTopicDescription" required></textarea>
            <button type="submit" name="edit_topic">Salvar</button>
        </form>
    </div>
</div>

<!-- Modal de Edição de Post -->
<div id="editPostModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editPostModal')">&times;</span>
        <h2>Editar Post</h2>
        <form id="editPostForm" method="POST">
            <input type="hidden" name="id_post" id="editPostId" value="">
            <label>Título:</label>
            <input type="text" name="titulo" id="editPostTitle" required>
            <label>Corpo:</label>
            <textarea name="corpo" id="editPostBody" required></textarea>
            <button type="submit" name="edit_post">Salvar</button>
        </form>
    </div>
</div>

<script>
    function openModal(modalId, fields) {
        document.getElementById(modalId).style.display = "block";
        fields.forEach(field => {
            document.getElementById(field.id).value = field.value;
        });
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    // Fechar o modal ao clicar fora da área do conteúdo
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal(event.target.id);
        }
    }
</script>

<?php include_once('../includes/footer.php'); ?>
</body>
</html>