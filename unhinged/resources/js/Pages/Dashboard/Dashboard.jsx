import React, { useState } from 'react';

// Parent Components
import Navigation from './Components/Navigation';
//import ActionBar from './Components/ActionBar';

// Listing Related Components
//import TicketList from '/Components/Queue/List';
//import TicketDetail from '/Components/Queue/Detail';
//import TicketItem from '/Components/Queue/Item';

// Filters
//import FilterPanel from '/Components/Filters/Panel';
//import FilterItem from '/Components/Filters/Item';

// Stats Pane
//import StatsPanel from '/Components/Stats/Panel';
//import StatsItem from '/Components/Stats/Item';

const Dashboard = () => {
    const [currentView, setCurrentView] = React.useState('Tickets');

    const handleViewChange = (view) => {
        setCurrentView(view);
        setSelectedTicket(null);
    };

    return (
        <div className="dashboard">
            <div className="mainPanel">
                <Navigation />
            </div>
        </div>
    );
};

export default Dashboard;
