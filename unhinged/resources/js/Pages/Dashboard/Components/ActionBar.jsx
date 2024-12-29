import React from 'react';

const ActionBar = ({ currentView, handleViewChange }) => {
    return (
        <div className="actionBar">
            <button 
                onClick={() => handleViewChange('tickets')}
                className={`${currentView === 'tickets' ? 'active' : ''}`}
                >
                Search & Filter Tickets
            </button>
            <button 
                onClick={() => handleViewChange('stats')}
                className={`${currentView === 'stats' ? 'active' : ''}`}
                >
                View Stats
            </button>
        </div>
    );
};

export default ActionBar;