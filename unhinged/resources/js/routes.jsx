import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import Login from './Pages/Auth/Login';
import Dashboard from './Pages/Dashboard/Dashboard';

const ProtectedRoute = ({ children }) => { 
    const authToken = document.cookie.split('; ').find(row => row.startsWith('adminToken=')); 
    const isAuthenticated = authToken && authToken.split('=')[1] === 'TotallySecureEncryptedTokem';
    
    return isAuthenticated ? children : <Navigate to="/login" replace />;
};


const AppRoutes = () => {
    return (
        <Routes>
            <Route path="/login" element={<Login />} />
            <Route path="/*" element={
                <ProtectedRoute>
                    <Dashboard />
                </ProtectedRoute>
            } />
        </Routes>
    );
};

export default AppRoutes;