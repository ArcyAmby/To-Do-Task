<template>
  <div class="container-fluid m-0">
    <Header />
    <header>
      <div class="app-header w-auto">
        <div class="container">
          <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            
            <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
              <li>
                <button class="fancy-button home-button" @click="goHome">
                  <i class="fas fa-home"></i> Home
                </button>
              </li>
              <li>
                <!-- Open the modal for new task creation -->
                <button @click="showNewTaskModal" class="fancy-button new-task-button">
                  <i class="fas fa-plus"></i>
                  New Task
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>

    <div class="card">
      <div class="card-body">
        <div class="container-fluid">
          <!-- Pass the tasks data prop to the TaskList component -->
          <!-- Pass the method reference for task deletion -->
          <TaskList :tasks="tasks" @task-delete="onTaskDelete" @update-tasks="onUpdateTasks" />
        </div>
      </div>
    </div>

    <!-- New Task Modal -->
    <div v-if="showModal" class="modal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content fancy-modal">
          <div class="modal-header text-white">
            <h3 class="modal-title">New Task</h3>
            <button type="button" class="close" @click="showModal = false">&times;</button>
          </div>
          <div class="modal-body">
            <i v-if="!isSaving" class=""></i>
            <i v-else class="fas fa-spinner fa-spin"></i>
            <!-- Include the TaskOption component within the modal -->
            <TaskOption :isNewTask="true" :task="emptyTask" @task-created="createTask" @new-task-created="onNewTaskCreated"
            @task-updated="onUpdateTask" />
            
          </div>
        </div>
      </div>
    </div>
    <Footer />
  </div>
</template>

<script>
import TaskOption from './TaskOption.vue';
import TaskList from './TaskList.vue';
import axios from 'axios';

export default {
  name: 'App',
  components: {
    TaskOption,
    TaskList
  },
  data() {
    return {
      showModal: false,
      emptyTask: { // Provide an empty task object
        title: '',
        description: '',
        status: '',
        file: null
      },
      tasks: [], // Initialize an empty tasks array
      isNewTask: true,
      selectedTask: null,
      isLoading: false, // Add this line to define the isLoading property
      isSaving: false,
    };
  },
  methods: {
    showNewTaskModal() {
      this.isNewTask = true;
      this.selectedTask = null;
      this.showModal = true;
    },

    async createTask(newTask) {
      try {
        this.isSaving = true;
        // Call the create endpoint using Axios
        const response = await axios.post('/api/tasks', newTask);
        // Set the ID returned from the server to the new task
        newTask.id = response.data.id;
        // Add the new task to the local tasks array
        this.tasks.push(newTask);
        // Close the new task modal
        this.showModal = false;
        // Emit a custom event to notify the parent component (App) about the new task creation
        this.$emit('new-task-created', newTask);
        console.log('API Response for new task:', newTask);
      } catch (error) {
        console.error('Error creating task:', error);
      } finally {
        this.isSaving = false; // Hide the loading spinner after saving, whether it succeeded or not
      }
    },


    async onTaskDelete(deletedTaskId) {
      try {
        // Call the delete endpoint using Axios
        await axios.delete(`/api/tasks/${deletedTaskId}`);
        // Handle success (e.g., show a success message, update the list, etc.)
        console.log('Task deleted successfully');
        this.fetchTasks();
      } catch (error) {
        // Handle error (e.g., show an error message)
        console.error('Error deleting task:', error);
      }
    },
    async fetchTasks() {
      try {
        const response = await axios.get('/api/tasks');
        console.log('API Response:', response.data);
        this.tasks = response.data;
      } catch (error) {
        console.error(error);
      }
    },

    onUpdateTask(updatedTask, taskId) {
      // Update the tasks array with the updated task data
      this.tasks = this.tasks.map((task) => (task.id === taskId ? updatedTask : task));
    },

    onUpdateTasks(updatedTasks) {
      this.tasks = updatedTasks;
    },

    onNewTaskCreated(newTask) {
      // Close the new task modal
      this.showModal = false;
      // Add the new task to the local tasks array
      this.tasks.push(newTask);
    },

    editTask(task) {
      this.isNewTask = false;
      this.selectedTask = { ...task };
      this.showModal = true;
    },
    
    
  },
  emits: ['new-task-created'], // Define the custom event that the component can emit
  // Call the fetchTasks method in the created hook
  created() {
  this.fetchTasks();
  }

}
</script>

<style>
:root {
  --primary-color: #101820FF;
  --primary-light: #40464D;
  --secondary-color: #F2AA4CFF;
}

.container-fluid {
  margin: 0;
  padding: 0;
}

.app-header {
  background-color: var(--primary-light);
}


/* Style the modal */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-dialog {
  max-width: 400px;
  width: 90%;
}

/* Optional - Modify the modal header background color and text color */
.modal-header {
  background-color: var(--secondary-color);
  color: #fff;
}

/* Optional - Center the modal body text */
.modal-body {
  text-align: center;
}

/* Optional - Add some spacing between buttons in the modal footer */
.modal-footer button {
  background-color: var(--secondary-color);
  margin-right: 10px;
  border-style: none;
}
.button-container {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.fancy-button {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #fff;
  color: var(--primary-color);
  border: none;
  padding: 6px 18px;
  font-size: 14px;
  font-weight: bold;
  border-radius: 8px;
  cursor: pointer;
  margin: 10px;
  transition: background-color 0.3s ease;
}

.fancy-button i {
  margin-right: 10px;
}

.fancy-button:hover {
  background-color: #E89F36FF;
}

.design-modal {
  padding: 20px;
  border-radius: 10px;
  background-color: #ffffff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Align text to the left in the New Task Modal */
.modal-body {
  text-align: left;
}

 /* Add space between the buttons on small screens */
 .nav li {
  margin-bottom: 10px;
}




@media (max-width: 767px) {
  /* Center the buttons vertically on small screens */
  .app-header {
    display: flex;
    justify-content: space-between;
  }
}

</style>
