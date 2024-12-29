import React, { useState, useEffect } from 'react';

const Navigation = () => {

    // state and setting up our admin for per-son-al-ity
    const [user, setUser] = useState(null);

    useEffect(() => {
        const fetchAdmin = async () => {
            try {
                const response = await fetch('/api/users/admin');
                const data = await response.json();
                setUser(data);
            } catch (error) {
                console.error('Error fetching admin:', error);
            }
        };

        fetchAdmin();
    }, []);

    const firstName = user ? user.name.split(' ')[0] : 'Admin';

    const handleSignOut = () => {
        document.cookie = 'adminToken =; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        window.location.href = '/login';
    };

    return (
        <div className="Navigation">
        <span className="signOut">
            Not {firstName}? <a className="getOut" onClick={handleSignOut}>Sign Out</a>
        </span>
        <h1>Welcome {firstName}</h1>
        <p>Administrator</p>
    </div>
    );

}

export default Navigation;