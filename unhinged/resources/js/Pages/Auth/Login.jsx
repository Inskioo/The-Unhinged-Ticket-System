import React, { useState } from 'react'
import logo from '../../../assets/logo.svg';
import { useNavigate } from 'react-router-dom';

const Login = () => {
    const navigate = useNavigate();

    const [formData, setFormData] = useState({
        email: '',
        password: ''
    });

    const handleChange = (e) => { 
        const { name, value } = e.target; setFormData({ ...formData, [name]: value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (formData.email === '' && formData.password === 'password') {
            localStorage.setItem('adminToken', 'adminToken');
            navigate('/');
        } else {
            // Todo - Add Error Handling
        }
    }

    const content = {
        logo: logo,
        title: 'Unhinged Client Support System',
        subtitle: 'Protecting our primary support channels so you dont have to',
        bottom: '<p>If you have forgotten your password, please contact a member of the admin team.<br><br>If you\'re not a member of the team, ask yourself, <b>why are you here?</b></p>'
    };

    
    return (
        <div className="login">
            <div className="logo"><img src={content.logo} alt="Logo" /></div>
            <div className="title"><h1>{content.title}</h1></div>
            <div className="subtitle"><h2>{content.subtitle}</h2></div>
            <form className="loginForm" onSubmit={handleSubmit}>
                <input 
                    className="email"
                    type="email" 
                    placeholder="Admin Email" 
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                />
                <input 
                    className="password"
                    type="password" 
                    placeholder="Password" 
                    name="password"
                    value={formData.password}
                    onChange={handleChange}
                />
                <button type="submit">Sign In</button>
            </form>
            <div className="bottom" dangerouslySetInnerHTML={{ __html: content.bottom }}/>
        </div>
    );
}

export default Login;