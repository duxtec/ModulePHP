// URL da API de exemplo
const apiUrl = 'https://example.com/developers';

// Configuração do objeto de requisição
const requestOptions = {
  method: 'POST', // Método POST para criar um novo recurso
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    name: 'Thiago Costa Pereira',
    altura: 1.80,
  })
};

// Função para fazer a requisição usando Fetch API
fetch(apiUrl, requestOptions)
  .then(response => {
    // Verifica se a requisição foi bem sucedida (código 200 a 299)
    if (response.ok) {
      console.log('Requisição bem sucedida!');
      // Código 204 (No Content) - resposta sem conteúdo
      if (response.status === 204) {
        console.log('Nenhum conteúdo retornado.');
        return null;
      }
      // Código 201 (Created) - novo recurso criado
      else if (response.status === 201) {
        return response.json();
      }
      // Código 200 (OK) - retorno padrão
      else {
        return response.json();
      }
    }
    // Código 400 (Bad Request) - requisição inválida
    else if (response.status === 400) {
      throw new Error('Requisição inválida.');
    }
    // Código 500 (Internal Server Error) - erro no servidor
    else if (response.status >= 500) {
      throw new Error('Erro no servidor.');
    }
    // Outros códigos de erro não esperados
    else {
      throw new Error(`Erro de HTTP! Status: ${response.status}`);
    }
  })
  .then(data => {
    // Manipula os dados da resposta, se houver
    if (data) {
      console.log('Dados recebidos:', data);
      // Exemplo de utilização dos dados recebidos
      const postId = data.id;
      console.log('ID do novo post:', postId);
    }
  })
  .catch(error => {
    // Captura e trata erros de requisição
    console.error('Erro na requisição:', error.message);
  });
