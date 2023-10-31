import { useState } from 'react';
import { Link } from 'react-router-dom';
import axios from "axios";

function Login() {
  const [inputs, setInputs] = useState({ username: '', password: '' });

  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;
    setInputs(values => ({...values, [name]: value}))
  }

  const handleSubmit = async (event) => {

    try {
      const response = await axios.post('http://localhost/index.php/user/login', inputs);
      const { message } = response.data;
      
      if (response.status === 200) {
        alert('Registration successful!');
      }
    } catch (error) {
      console.error('API call error:', error);
      if (error.response && error.response.data && error.response.data.error) {
        alert(error.response.data.error); 
      } else {
        alert('An error occurred');
      }
    }
  }

  return (
    <div>
        <h1>Login</h1>
        <form onSubmit={handleSubmit}>
        <label>Username:
        <input 
            type="text" 
            name="username" 
            value={inputs.username || ""} 
            onChange={handleChange}
        />
        </label>
        <br/>
        <label>Password:
            <input 
            type="password" 
            name="password" 
            value={inputs.password || ""} 
            onChange={handleChange}
            />
            </label>
            <br/>
            <input type="submit" />
            <br/>
            <Link to="/registration">Don't have an account? Sign up here.</Link>
        </form>
    </div>
  )
}

export default Login;