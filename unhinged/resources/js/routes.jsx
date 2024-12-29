import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import Login from './Pages/Auth/Login';

const ProtectedRoute = ({ children }) => {
    const isAuthenticated = localStorage.getItem('adminToken');
    return isAuthenticated ? children : <Navigate to="/login" replace />;
};

const AppRoutes = () => {
    return (
        <Routes>
            <Route path="/login" element={<Login />} />
            <Route path="/*" element={
                <ProtectedRoute>
                    <div>Boom logged in dashboard</div>
                </ProtectedRoute>
            } />
        </Routes>
    );
};

export default AppRoutes;