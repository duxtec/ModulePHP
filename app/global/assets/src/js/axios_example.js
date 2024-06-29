// Importar o Axios (certifique-se de instalá-lo primeiro: npm install axios)
const axios = require('axios');

// URL da API de exemplo
const apiUrl = 'https://jsonplaceholder.typicode.com/posts';

// Objeto com os dados a serem enviados na requisição POST
const postData = {
  title: 'Novo post',
  body: 'Conteúdo do novo post',
  userId: 1
};

// Fazer a requisição usando Axios

axios.post(apiUrl, postData)
  .then(response => {
    // Verifica se a requisição foi bem sucedida (código 200 a 299)
    if (response.status >= 200 && response.status < 300) {
      console.log('Requisição bem sucedida!');
      // Código 204 (No Content) - resposta sem conteúdo
      if (response.status === 204) {
        console.log('Nenhum conteúdo retornado.');
        return null;
      }
      // Código 201 (Created) - novo recurso criado
      else if (response.status === 201) {
        return response.data;
      }
      // Código 200 (OK) - retorno padrão
      else {
        return response.data;
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
