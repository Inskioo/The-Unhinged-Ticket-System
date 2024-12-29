import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import '../css/app.css';
import { BrowserRouter } from 'react-router-dom';
import AppRoutes from './routes';

const App = () => {
    return (
        <BrowserRouter>
            <AppRoutes />
        </BrowserRouter>
    );
};

createRoot(document.getElementById('app')).render(<App />);