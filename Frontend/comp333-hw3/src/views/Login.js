import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useNavigate } from "react-router-dom";
import axios from 'axios';

function Login() {
  const [inputs, setInputs] = useState({ username: '', password: '' });
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleChange = (event) => {
    const { name, value } = event.target;
    setInputs((values) => ({ ...values, [name]: value }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      // Assuming your API expects query parameters for a GET request
      const response = await axios.get('http://localhost/index.php/user/login', {
        params: inputs
      });
      if (response.status === 200) {
        alert('Login successful!');
        sessionStorage.setItem('username', inputs.username);
        navigate("/");

      } else {
        alert('Login failed. Please try again.');
      }
    } catch (error) {
      console.error('API call error:', error);

      if (error.response && error.response.data && error.response.data.error) {
        alert(error.response.data.error);
      } else {
        alert('An error occurred');
      }
    }
  };

  return (
    <div>
      <h1>Login</h1>
      <form onSubmit={handleSubmit}>
        <label>
          Username:
          <input
            type="text"
            name="username"
            value={inputs.username || ''}
            onChange={handleChange}
          />
        </label>
        <br />
        <label>
          Password:
          <input
            type="password"
            name="password"
            value={inputs.password || ''}
            onChange={handleChange}
          />
        </label>
        <br />
        <input type="submit" />
        <br />
        {error && <div className="error-message">{error}</div>}
        <Link to="/registration">Don't have an account? Sign up here.</Link>
      </form>
    </div>
  );
}

export default Login;