<template>

    <div class="container-fluid m-0">
      <header>
        <div class="app-header px-3 py-2 w-auto text-white">
          <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
              <router-link to="/" class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
                <img src="/assets/img/logo.png" alt="logo-todo" style="height:60px; padding-top: 4px; padding-bottom: 4px;"/>
              </router-link>
              <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                <li>
                    <router-link to="/" class="nav-link text-white" style="text-decoration: none; color: white;">
                      <i class="fas fa-home"></i>
                      Home
                      </router-link>
                </li>
                <li>
                    <router-link to="/tasks/create" class="nav-link text-white" style="text-decoration: none; color: white;">
                      <i class="fas fa-plus-circle"></i>
                      New Task
                      </router-link>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </header>
    
      <div class="card">
        <div class="card-body">
          <div class="container-fluid">
            <router-view></router-view>
          </div>
        </div>
      </div>
    
    </div>
    
    </template>
    
    <script>
    export default {
      name: 'App'
    }
    </script>

  <style>

  :root {
    --primary-color: #101820FF;
    --secondary-color: #F2AA4CFF;
  }

  .container-fluid{
    margin: 0;
    padding: 0;
  }
  .app-header{
    background-color: var(--primary-color)
  }
  
  </style>