import { useState } from 'react';
import { Link } from 'react-router-dom';
import axios from "axios";

function Login() {
  const [inputs, setInputs] = useState({});

  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;
    setInputs(values => ({...values, [name]: value}))
  }

  const handleSubmit = async () => {
    /**
    let res = await axios.get("https://localhost/index.php/user/login", {
        username,
        password
    });
    */
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
            type="text" 
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