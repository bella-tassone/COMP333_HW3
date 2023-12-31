import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useNavigate } from "react-router-dom";
import axios from 'axios';
import { UncontrolledTooltip } from 'reactstrap';
import './Ratings.css';

function Login() {

  const [inputs, setInputs] = useState({ username: '', password: '' });
  const [error, setError] = useState('');
  const navigate = useNavigate();

  // user shouldn't get to login if already logged in
  if(localStorage.getItem('username')) {
    navigate("/");
  }

  // when users enter values
  const handleChange = (event) => {
    const { name, value } = event.target;
    setInputs((values) => ({ ...values, [name]: value }));
  };

  // when submit button is clicked
  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      // Assuming your API expects query parameters for a GET request
      const response = await axios.get('http://localhost/index.php/user/login', {
        params: inputs
      });
      if (response.status === 200) {
        alert('Login successful!');
        localStorage.setItem('username', inputs.username);
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
            style={{marginBottom:'10px'}}
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
        <input id='submit-login' type="submit" style={{marginTop:'10px', marginLeft:'90px', marginBottom:'5px'}}/>
        <UncontrolledTooltip target='submit-login' placement='right' style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginLeft:'5px'}}>Submit to login<br/>to your account!</UncontrolledTooltip>
        <br />
        {error && <div className="error-message">{error}</div>}
        <Link to="/registration">Don't have an account? Sign up here.</Link>
      </form>
      <div>
        <button className="home-button" id='home' onClick={() => navigate("/")}>
          Home
        </button>
        <UncontrolledTooltip target='home' placement='left' style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginRight:'5px', marginTop:'5px'}}>Return to the<br/>main page!</UncontrolledTooltip>
      </div>
    </div>
  );
}

export default Login;