import { useState } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import { useNavigate } from "react-router-dom";
import { UncontrolledTooltip } from 'reactstrap';
import './Ratings.css';


function Registration() {
  const [inputs, setInputs] = useState({ username: '', password1: '', password2: ''});
  const navigate = useNavigate();

  // user shouldn't get to registration if already logged in
  if(localStorage.getItem('username')) {
    navigate("/");
  }
   
  // when users enter values
  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;
    setInputs((values) => ({ ...values, [name]: value }));
  }

  // when submit button is clicked
  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      const response = await axios.post('http://localhost/index.php/user/create', inputs);
      const { message } = response.data;
      
      if (response.status === 200) {
        alert('Registration successful!');
        localStorage.setItem('username', inputs.username);
        navigate("/");
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
      <h1>Registration</h1>
      <form onSubmit={handleSubmit}>
        <label>Username:
          <input
            type="text"
            name="username"
            value={inputs.username}
            onChange={handleChange}
            style={{marginBottom:'10px'}}
          />
        </label>
        <br />
        <label>Password:
          <input
            type="password"
            name="password1"
            value={inputs.password1}
            onChange={handleChange}
            style={{marginBottom:'10px'}}
          />
        </label>
        <br />
        <label>Re-enter<br/>Password:
          <input
            type="password"
            name="password2"
            value={inputs.password2}
            onChange={handleChange}
          />
        </label>
        <br />
        <input id='submit-register' type="submit"  style={{marginTop:'10px', marginLeft:'90px', marginBottom:'5px'}} />
        <UncontrolledTooltip target='submit-register' placement='right' style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginLeft:'5px'}}>Submit to create<br/>your account!</UncontrolledTooltip>
        <br />
        <Link to="/login">Already have an account? Login here.</Link>
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

export default Registration;