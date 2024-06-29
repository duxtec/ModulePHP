# Module PHP

O Module PHP é um framework PHP modular que segue as melhores práticas e incorpora tecnologias avançadas para o desenvolvimento web. Ele foi desenvolvido com o objetivo de oferecer uma estrutura flexível e eficiente para a criação de aplicativos web robustos e escaláveis, focando especialmente na criação de aplicativos web com múltiplos níveis de usuário.

<p align="center"><a href="https://modulephp.com" target="_blank"><img src="./app/global/assets/src/img/logo.webp" width="400"></a></p>
<p align="center">
<a href="https://packagist.org/packages/duxtec/modulephp"><img src="https://img.shields.io/packagist/dt/duxtec/modulephp" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/duxtec/modulephp"><img src="https://img.shields.io/packagist/v/duxtec/modulephp" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/duxtec/modulephp"><img src="https://img.shields.io/packagist/l/modulephp/framework" alt="License"></a>

## Índice

1. [Funcionalidades](#funcionalidades)
2. [Instalação](#instalação)
3. [Primeiros Passos](#primeiros-passos)
4. [Estrutura de Diretórios](#estrutura-de-diretórios)
5. [Produção](#produção)
6. [Testes](#testes)
7. [Auditoria](#testes)
8. [Atualização](#atualização)
9. [Contribuição](#contribuição)
10. [Créditos](#créditos)
11. [Licença](#licença)
12. [Contato](#contato)
13. [Notas de Versão](#notas-de-versão)

## Funcionalidades:

- Construção Automática de Rotas: Implementa um sistema automatizado para a definição e gerenciamento de rotas, simplificando o processo de roteamento em aplicações web.

- Gerador de Build de Assets: Oferece um gerador de build para assets como JavaScript, CSS, imagens e vídeos, facilitando a preparação dos recursos para ambientes de produção.

- Criação Automatizada de APP PWA: Possui funcionalidades para automatizar a criação de Progressive Web Apps (PWA), permitindo uma experiência de usuário avançada.

- Integração com Lighthouse: Integra-se ao Lighthouse para avaliação automatizada da qualidade e desempenho da aplicação, auxiliando na otimização contínua.

- Automatização de Otimizações de Performance: Implementa recursos automatizados para otimizar o desempenho de carregamento da página, garantindo uma experiência ágil para o usuário.

- Suporte a Múltiplos Níveis de Usuário: Oferece suporte para múltiplos níveis de usuário, com encapsulamento para garantir maior segurança e controle de acesso.

- Recursos Globais: Disponibiliza recursos globais que atendem a todos os usuários, proporcionando consistência e eficiência no desenvolvimento.

- Conexão Automatizada com Banco de Dados e Email: Facilita a conexão automatizada com banco de dados MySQL e serviços de email, simplificando o desenvolvimento e a integração de funcionalidades.

- Facilidade de Mudança entre Ambientes: Permite uma fácil transição entre ambientes de desenvolvimento e produção, facilitando o processo de desenvolvimento e implantação.

- Scripts Facilitadores: Inclui scripts para facilitar a execução de ações no framework, como adição de níveis de usuários ou de usuários, agilizando tarefas comuns de administração.

- Bases de Código Comum: Fornece bases de código comuns, como header, footer, scripts, estilos e metatags, para evitar a repetição de código em todas as páginas, promovendo a reutilização e a manutenção eficiente.

- Verificador de Performance: Incorpora um verificador de desempenho que registra o tempo para cada ação dentro de uma requisição, permitindo a identificação e otimização de gargalos de desempenho.

- Recursos de Usuários: Oferece recursos para gerenciamento de usuários, incluindo login, logout, cadastro e mudança de senha de forma simplificada.

- Respostas Automatizadas em JSON: Implementa recursos para geração automatizada de respostas em JSON, facilitando a integração com APIs e serviços externos.

- Integração com Node.js: Integra-se ao Node.js para execução de recursos exclusivos, requerendo a instalação do Node na máquina para aproveitar funcionalidades específicas.

- Criador de Paletas de Cores: Inclui um criador de paletas de cores para adaptar códigos a diferentes identidades visuais, garantindo uma experiência personalizada para o usuário.

- Gerador de Logs: Possui um gerador de logs para facilitar a depuração e o monitoramento da aplicação, auxiliando no diagnóstico de problemas e na manutenção do sistema.

- Testes Unitários via PHPUnit: Oferece suporte para a realização de testes unitários através do PHPUnit, garantindo a qualidade e robustez do código desenvolvido.

- Essas são as principais funcionalidades do Projeto Module PHP, que visam proporcionar uma experiência de desenvolvimento web avançada e eficiente.

## Instalação

1. **Criação do Projeto via Composer:**
   Para começar, crie um novo projeto utilizando o Composer. Execute o seguinte comando no terminal:

   ```bash
   composer create-project duxtec/modulephp
   ```

2. **Instalação de Dependências:**
   Efetue a instalação d-as dependências do projeto executando o seguinte comando no terminal, dentro do diretório do projeto:

   ```bash
   composer install
   ```

3. **Preenchimento dos Arquivos de Configuração:**
   Após a criação do projeto, você precisa preencher os arquivos de configuração com as informações necessárias. Existem duas maneiras de fazer isso:

   - **Usando o Comando**

     ```bash
     php allay configure
     ```

     Para mais informações consulte [Scripts](#scripts).

     Este comando irá guiá-lo pelo processo de preenchimento das configurações necessárias.

   - **Manualmente:**
     Se preferir, você pode criar os arquivos `.env` manualmente dentro da pasta `config`, utilizando os arquivos `.env.example` como modelo. Copie os arquivos `.env.example` e renomeie-os para `.env`, em seguida, preencha os valores das variáveis de ambiente conforme necessário.

4. **Atualize o esquema do banco de dados**

   ```bash
   php allay update:schema
   ```

   Para mais informações consulte [Scripts](#scripts).

5. **Pronto para Começar o Desenvolvimento:**

Se você seguiu os passos acima, seu projeto já está configurado e pronto para o desenvolvimento! Agora, recomendamos que você:

- Leia a seção [Primeiros passos](#primeiros-passos) do README para obter orientações sobre como utilizar o framework para desenvolver um projeto.

- Explore a [documentação](#documentação) para informações mais detalhadas sobre os recursos, APIs e fluxos de trabalho disponíveis.

Divirta-se desenvolvendo!

Se precisar de ajuda ou tiver dúvidas, consulte a documentação ou entre em contato com a equipe de desenvolvimento.

## Primeiros Passos

### Níves de usuário

O diretório `app` é onde reside a maior parte do código do sistema. Ele contém pastas exclusivas para cada nível de usuário, garantindo encapsulamento e segurança. A estrutura é projetada para separar claramente as funcionalidades e permissões de diferentes tipos de usuários.

#### Criando Níveis de Usuário

Os níveis de usuário podem ser criados usando o comando:

```bash
php allay create:userlevel nome_do_nível_de_usuário
```

Para mais informações consulte [Scripts](#scripts).

Ao criar um nível de usuário, será criado um diretório correspondente dentro da pasta `app`. Além disso, o nível de usuário será registrado no banco de dados com um ID atribuído automaticamente.

#### Níveis Especiais

Existem dois níveis de usuário especiais:

- **public**: Representa os usuários não logados no sistema. O conteúdo nesta pasta é acessível a todos os visitantes não logados do site.
- **global**: Contém recursos compartilhados entre todos os usuários. Se um recurso solicitado não for encontrado na pasta específica do usuário, ele será buscado nesta pasta antes de retornar um erro de "recurso não encontrado".

#### Estrutura de Diretórios por Nível de Usuário

Para cada nível de usuário, a estrutura de diretórios dentro de app é semelhante, contendo pastas para models, controllers, views e assets. Isso garante que cada nível de usuário possa ter seu próprio conjunto de recursos e funcionalidades.

Essa organização permite uma clara separação e gerenciamento dos recursos e funcionalidades, facilitando a manutenção e a escalabilidade do sistema.

### Bases de Renderização
As bases de renderização são estruturas genéricas das páginas HTML que são compartilhadas entre diferentes vistas do aplicativo, elas podem ser feitas diretamente com HTML ou utilizando classes com um método `render()`. Isso proporciona flexibilidade no desenvolvimento, permitindo a escolha entre simplicidade e reutilização de componentes. Cada nível de usuário pode ter sua própria base de renderização na sua respectiva pasta ou usar uma base de renderização global.

As bases de rederização disponíveis são as seguintes:

- **view/base/head/metatag.php**: Define as metatags HTML que são usadas em todas as páginas, como descrições, palavras-chave, e outras informações de metadados.
- **view/base/head/script.php**: Inclui os scripts JavaScript que são carregados na seção `<head>` de todas as páginas.
- **view/base/head/style.php**: Inclui as folhas de estilo CSS que são carregadas na seção `<head>` de todas as páginas.
- **view/base/body/aside.php**: Define a seção de barra lateral (`aside`) que pode ser usada em diferentes páginas para navegação ou informações adicionais.
- **view/base/body/header.php**: Define o cabeçalho (`header`) comum que aparece no topo de todas as páginas.
- **view/base/body/main.php**: Define a seção principal (`main`) do corpo da página, onde o conteúdo principal é exibido.
- **view/base/body/footer.php**: Define o rodapé (`footer`) que aparece na parte inferior de todas as páginas.

Exemplos de Bases de renderização podem ser encontradas nas pastas correspondentes.

### Rotas

O framework utiliza três tipos principais de rotas para organização e funcionalidade, Essas convenções de roteamento ajudam a organizar e separar claramente os diferentes aspectos funcionais do seu aplicativo, garantindo uma estrutura limpa e modular.

Para criar uma nova rota, basta criar um arquivo no diretório correspondente e a automaticamente será criada uma rota daquele tipo.

As rotas são as seguintes:

1. **Rotas das Páginas (Rota Padrão)**
   - **Descrição**: Basta criar um arquivo na respectiva pasta que a rota é criada automaticamente.
   - **Diretório**: `app/USERLEVEL_NAME/view/pages`
   - **Condição de Roteamento:** Todas as rotas que não sejam `controller` ou `assets` serão redirecionadas para este diretório.

2. **Rota de Assets**
   - **Descrição**: Essas rotas são usadas para servir arquivos estáticos como JavaScript, CSS, imagens, e outros recursos necessários para a interface do usuário. Durante o desenvolvimento, os arquivos são servidos do diretório `src`, enquanto na produção os arquivos são gerados no diretório `build` após serem otimizados. Para mais informações consulte [Produção](#producao).
   - **Diretórios**:
     - Desenvolvimento: `app/USERLEVEL_NAME/assets/src`
     - Produção: `app/USERLEVEL_NAME/assets/build`.

   - **Condição de Roteamento:** Todas as rotas iniciadas com "assets/" serão redirecionadas para este diretório.

3. **Rota de Controladores**
   - **Descrição:** Essas rotas têm, por padrão, a comunicação em JSON e podem ser usadas para desenvolvimento de APIs Restful.
   - **Diretório:** `app/USERLEVEL_NAME/controller`
   - **Condição de Roteamento:** Todas as rotas iniciadas com "controller/" serão redirecionadas para este diretório.

### Exemplos

Ao instalar o framework, você terá acesso a exemplos práticos que ajudam a entender melhor como utilizar suas funcionalidades principais. Os exemplos podem ser encontrados nas seguintes localizações:

- app/USERLEVEL_NAME/view/base
- app/USERLEVEL_NAME/view/pages
- app/USERLEVEL_NAME/assets/src
- app/USERLEVEL_NAME/controller
- app/USERLEVEL_NAME/model

Na [documentação](#documentação) (em desenvolvimento), você também encontrará exemplos detalhados e guias passo a passo para diversas funcionalidades do framework.

### Configuração e Execução em Modo de Desenvolvimento

Para iniciar seu projeto em um ambiente de desenvolvimento local, siga os passos abaixo:

1. Configuração Inicial:
   - Verifique se o arquivo de configuração config/system.env está configurado corretamente. Certifique-se de que a variável PRODUCTION esteja definida como false. Isso garante que o ambiente esteja configurado para desenvolvimento.

2. Execução do Servidor Local:
   - Utilize o comando abaixo para iniciar o servidor local. Esse comando iniciará o servidor na porta padrão 93.

      ```bash
      php allay localhost
      ```

      Para mais informações consulte [Scripts](#scripts).

      Certifique-se de que a porta 93 esteja aberta e disponível para uso. Dependendo das permissões do usuário, pode ser necessário executar o comando com privilégios elevados (por exemplo, usando sudo).

## Estrutura de Diretórios

### Principais Diretórios

**app**: Este diretório contém uma pasta correspondente para cada nível de usuário. Essa organização garante um encapsulamento de usuários, proporcionando maior segurança.
- **Níveis de Usuário Especiais**:
  - **public**: Nível de usuário para usuários não logados no sistema.
  - **global**: Nível de usuário que serve recursos para todos os outros. Se um recurso solicitado não estiver disponível para um usuário específico, ele será procurado no nível `global` antes de informar que não existe.
- **Subdiretórios**:
   - **model**: Contém os modelos que representam a lógica de negócios e a estrutura dos dados do aplicativo.
   - **controller**: Contém os controladores que lidam com a lógica de aplicação e orquestram as respostas às solicitações de entrada.
   - **view/base**: Contém as estruturas genéricas das páginas que são compartilhadas entre diferentes vistas do aplicativo.
      - **view/base/head/metatag.php**: Define as metatags HTML que são usadas em todas as páginas, como descrições, palavras-chave, e outras informações de metadados.
      - **view/base/head/script.php**: Inclui os scripts JavaScript que são carregados na seção `<head>` de todas as páginas.
      - **view/base/head/style.php**: Inclui as folhas de estilo CSS que são carregadas na seção `<head>` de todas as páginas.
      - **view/base/body/aside.php**: Define a seção de barra lateral (`aside`) que pode ser usada em diferentes páginas para navegação ou informações adicionais.
      - **view/base/body/header.php**: Define o cabeçalho (`header`) comum que aparece no topo de todas as páginas.
      - **view/base/body/main.php**: Define a seção principal (`main`) do corpo da página, onde o conteúdo principal é exibido.
      - **view/base/body/footer.php**: Define o rodapé (`footer`) que aparece na parte inferior de todas as páginas.
   - **view/pages**: Contém o conteúdo das páginas do aplicativo que os usuários visualizam. Cada arquivo ou subdiretório dentro de `pages` representa uma página individual no site.
   - **view/modules**: Contém módulos reutilizáveis ou componentes que podem ser incluídos em várias páginas. Esses módulos funcionam como componentes que encapsulam partes específicas da interface do usuário, facilitando a reutilização e manutenção.
   - **view/assets**:  Contém todos os recursos estáticos, como imagens, arquivos JavaScript, e folhas de estilo CSS. Durante o desenvolvimento, os arquivos são mantidos em `src`. Na produção, os arquivos compilados e minificados são armazenados em `build`.
  
**config**: Armazena as configurações do sistema, como dados de acesso ao banco de dados e email. Também contém informações sobre o site, como nome, descrição, cores principais, links para outras redes, etc. Para mais informações consulte o item 3 da [instalação](#instalação)

**database**: Onde ficam as entidades e cache do banco de dados, que serão carregados pelo ORM Doctrine. Para mais informações, consulte a [documentação do Doctrine ORM](https://www.doctrine-project.org/projects/doctrine-orm/en/3.2/index.html).

**logs**: Pasta onde são salvos os logs de erro ou de auditoria. Os logs de erro são gerados automaticamente pelo framework usando o Monolog. Os logs de auditoria podem ser feitos usando o comando `php allay lighthouse`, essa funcionalidade depende do Node e de um navegador compatível com o Lighthouse, como o Chrome, para mais informações consulte a [documentação do Lighthouse CLI](https://github.com/GoogleChrome/lighthouse?tab=readme-ov-file#using-the-node-cli)).

**public_html**: Contém o `.htaccess` e o `index.php`. A raiz pública do servidor web deve apontar para este diretório para um correto funcionamento do framework.

**resources**: Contém as funcionalidades nativas do framework. Não é recomendável editar este diretório, pois pode afetar a segurança do framework e esses arquivos podem ser reescritos durante uma atualização.
- **Atualizações**: Antes de atualizar, leia com cautela a seção [Atualização](#atualização).
- **Compatibilidade**: O upgrade ou downgrade só é compatível entre versão major iguais. Exemplo: qualquer versão `1.x.y` é compatível entre si, mas não com as versões `2.x.y` ou `3.x.y`.)
- **Colaboração**: Se você tem interesse em contribuir com a atualização do framework, as funcionalidades desenvolvidas/alteradas devem ficar dentro dessa pasta. Sua contribuição é muito bem-vinda, faça um Pull Request :).

**scripts**: Contém os scripts utilizados pelo framework. Não é recomendável editar os scripts presentes nesta pasta. Para execução dos scripts, utilize a interface de linha comando "allay".
- **Execução de Scripts**: Não é recomendado a execução de um script diretamente pelo seu arquivo. Para executar um script, use `php allay nome_do_script`. Para selecionar dinamicamente o script desejado, digite somente `php allay` para acessar o seletor de scripts. Para obter ajuda sobre um script, use `php allay nome_do_script -h` ou `php allay nome_do_script --help`.

**tests**: Onde devem ser feitos os testes do PHPUnit. Em caso de dúvidas, consulte a documentação do PHPUnit.[documentação do PHPUnit](https://phpunit.de/documentation.html)

**vendor**: Diretório onde ficam as instalações de bibliotecas do composer.

## Produção

Para configurar e executar seu framework em ambiente de produção, siga os passos abaixo:

1. **Configuração para Produção:**
   - Abra o arquivo de configuração `config/system.env`.
   - Altere a variável `PRODUCTION` para `true`.

2. **Build dos Assets:**
   - Execute o seguinte comando para fazer a build dos assets:
     ```bash
     php allay build
     ```
   - Este comando compilará e preparará os assets para serem utilizados em produção.

3. **Implantação no Servidor:**
   - Certifique-se de que a pasta pública do servidor web aponte para `public_html`.
   - Transfira todos os arquivos e diretórios gerados após a build para a pasta `public_html` do seu servidor.

4. **Testando Localmente:**
   - Para testar a versão de produção localmente, execute o seguinte comando:
     ```bash
     php allay localhost
     ```
   - Este comando realizará a build dos assets localmente e iniciará um servidor local na porta 93 com as configurações de produção.

5. **Acesso ao Sistema:**
   - Após configurar e implantar seu sistema em produção, acesse-o através do navegador utilizando o endereço correspondente ao seu servidor web configurado com a pasta `public_html`.

Certifique-se de seguir esses passos com cuidado para garantir que seu framework esteja configurado corretamente e funcionando adequadamente em ambiente de produção.

## Testes

Para executar testes siga os passos abaixo:

1. **Localização dos Testes:**
   - Os testes estão localizados na pasta `/tests`.
   - Para adicionar novos testes ou executar testes existentes, navegue até essa pasta.

2. **Utilização do PHPUnit:**
   - Os testes são executados utilizando o PHPUnit.
   - Consulte a documentação oficial do PHPUnit para aprender como escrever e executar testes específicos.


## Auditoria

Para realizar uma auditoria do seu projeto localmente, siga os passos abaixo:

1. **Execução do Lighthouse:**
   - Certifique-se de que o seu projeto está em execução localmente.
   - Execute o comando `php allay lighthouse` no terminal.
   - É necessário ter o Node.js instalado no seu sistema.
   - O Lighthouse requer um navegador compatível, como o Chrome, para realizar a auditoria.

2. **Localização dos Logs:**
   - Os resultados da auditoria serão salvos no diretório `/logs/lighthouse`.
   - Cada auditoria gerada será armazenada em um arquivo HTML nomeado com a data e hora da execução.

A auditoria com o Lighthouse pode ajudar a garantir que seu projeto atenda aos padrões de desempenho, acessibilidade, boas práticas e SEO. Utilize essas informações para otimizar e melhorar seu projeto conforme necessário.


## Atualização

O upgrade ou downgrade só é compatível entre versão major iguais. Exemplo: qualquer versão `1.x.y` é compatível entre si, mas não com as versões `2.x.y` ou `3.x.y`.)

Para atualizar o framework, basta substituir o diretório `resources` do sistema  pelo diretório `resources` da versão desejada, ou executar o script de atualização (em desenvolvimento) `php allay update` que faz este processo de forma automática.

Mesmo em atualizações compatíveis, é altamente recomendado a realização do backup do sistema anterior e testes em ambiente de desenvolvimento antes de disponibilizar as versões em produção, não nos responsabilizamos por atualizações mal efetuadas ou comportamente inesperados, atualize com responsabilidade e por sua conta em risco.

## Contribuição

O projeto está aberto para contribuições de todos os desenvolvedores interessados. Aqui estão algumas maneiras de contribuir:

- **Relatando Problemas (Issues)**:
  - Se você encontrar algum problema ou bug no framework, sinta-se à vontade para [relatar através das issues do GitHub](https://github.com/duxtec/ModulePHP/issues).
  - Certifique-se de incluir o máximo de detalhes possível e, se aplicável, anexe capturas de tela ou trechos de código.

- **Envio de Pull Requests**:
  - Contribuições através de pull requests são bem-vindas para correções de bugs, novos recursos ou melhorias na documentação.
  - Antes de enviar um pull request, certifique-se de que seu código segue as diretrizes de estilo e passou nos testes.

- **Melhorias na Documentação**:
  - A documentação é essencial para facilitar o uso do framework. Se você identificar áreas que podem ser melhoradas na documentação existente, sinta-se à vontade para fazer sugestões ou enviar alterações.

- **Compartilhamento e Feedback**:
  - Compartilhe o framework com outros desenvolvedores, contribua para discussões em fóruns ou redes sociais.
  - Se você tiver qualquer feedback ou sugestão para melhorar o framework, não hesite em entrar em contato através dos canais de contato fornecidos.

### Diretrizes de Contribuição

Para garantir que as contribuições sejam suaves e eficazes, por favor, siga estas diretrizes:

- **Padrões de Codificação**: Adote os padrões de codificação utilizados no projeto para manter a consistência.
- **Testes**: Certifique-se de que todas as alterações estão cobertas por testes unitários apropriados.
- **Documentação**: Documente qualquer nova funcionalidade ou alteração na documentação, se aplicável.
- **Respeito ao Código de Conduta**: Respeite o código de conduta do projeto e trate todos os participantes com respeito e cortesia.

### Agradecimentos aos Contribuidores

- Agradecemos a todos os desenvolvedores que contribuíram para o projeto até o momento. Seu apoio é fundamental para o sucesso contínuo do framework.


## Créditos

Desenvolvido pela empresa: Dux Tecnologia.

Desenvolvido pelo desenvolvedor: Thiago Costa Pereira.

Agradecimentos a: Leonardo Coelho por entusiasta e testador.

## Licença

Este projeto está licenciado sob a [Licença MIT](./LICENSE), incentivando a criação de softwares sob a Licença GPL-3.0 e o compartilhamento do código fonte.


## Contato

Para mais informações ou suporte, entre em contato:

- **E-mail**: [contato@tpereira.com.br](mailto:contato@tpereira.com.br)
- **WhatsApp**: [+55 21 98903-2187](https://wa.me/5521989032187)
- **Site**: [https://tpereira.com.br](https://tpereira.com.br)

## Notas de Versão

Versão 1.0.0 (Primeira Versão Estável)

- Esta é a primeira versão estável do framework.
- Inclui todas as funcionalidades principais descritas na documentação.
- Correções de bugs e melhorias de desempenho.
