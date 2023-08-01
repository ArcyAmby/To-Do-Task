<template>
  <header>
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <router-link to="/" class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
        <img src="/assets/img/logo.png" alt="logo-todo" style="height:60px; padding-top: 8px; padding-bottom: 8px;"/>
        <p class="logo-text">Simple To Do List with CRUD</p>
      </router-link>
      
      <div class="icons-container">
        <a href="https://www.linkedin.com/in/arsenio-ambayec" target="_blank" class="icon-link" @mouseover="hoveredIcon = 'linkedin'" @mouseout="hoveredIcon = ''">
          <i :class="['fab', 'fa-linkedin-in', { 'hovered': hoveredIcon === 'linkedin' }]"></i>
        </a>
        <a href="#" target="_blank" class="icon-link" @mouseover="hoveredIcon = 'discord'" @mouseout="hoveredIcon = ''">
          <i :class="['fab', 'fa-discord', { 'hovered': hoveredIcon === 'discord' }]"></i>
        </a>
        <a href="https://github.com/ArcyAmby" target="_blank" class="icon-link" @mouseover="hoveredIcon = 'github'" @mouseout="hoveredIcon = ''">
          <i :class="['fab', 'fa-github', { 'hovered': hoveredIcon === 'github' }]"></i>
        </a>
      </div>
    </div>
  </nav>
</header>
</template>

<script>
export default {
};
</script>

<style>
:root {
  --primary-color: #101820FF;
  --primary-light: #40464D;
  --secondary-color: #F2AA4CFF;
}

.navbar{
  background-color: var(--primary-color);
}

.icon-link {
  color: white;
  font-size: 1.2rem;
  margin-left: 10px;
  transition: color 0.3s ease; 
}

.icon-link:hover {
  color: var(--secondary-color); 
}

.hovered {
  color: var(--secondary-color);
}

.logo-text{
  font-size: 12px;

}
</style>