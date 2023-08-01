<template>
    <div class="filler-container"></div>
    <footer class="bg-light text-center py-3">
      <div class="container">
        <!-- Add your footer content here -->
        <p>&copy; {{ currentYear }} ToDo. All rights reserved.</p>
      </div>
    </footer>
  </template>
  
  <script>
  export default {
    data() {
      return {
        currentYear: new Date().getFullYear(),
      };
    },
    // You can add component-specific logic here if needed
  };
  </script>
  
  <style>
  .filler-container {
    height: 200px; /* Adjust the height as needed */
  }
  
  .footer {
    padding: 20px 0;
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    text-align: center;
  }
  </style>