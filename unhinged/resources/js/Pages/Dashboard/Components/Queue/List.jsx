import React, { useState, useEffect, refreshTrigger } from 'react';

const TicketList =({ filters, onTicketSelect }) => {
    const [tickets, setTickets] = useState(() => {
        const cachedTickets = localStorage.getItem('cachedTickets');
        return cachedTickets ? JSON.parse(cachedTickets) : [];
    });
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const [viewDescription, setViewDescription] = useState('Currently Showing All');

    useEffect(() => {
        setCurrentPage(1);
        fetchTickets();
    }, [filters]);

    useEffect(() => {
        fetchTickets();
    }, [currentPage, refreshTrigger ]);

    useEffect(() => {
        fetchTickets();
    }, [currentPage, filters, refreshTrigger])

    const fetchTickets = async () => {
        try{

            let url =  `/api/tickets?page=${currentPage}`;
            if (filters.status) url += `&status=${filters.status}`;
            if (filters.type) url += `&type=${filters.type}`;
            if (filters.priority) url += `&priority=${filters.priority}`;
            if (filters.supportAgent) url += `&assigned_to=${filters.supportAgent}`;
            if (filters.customerSearch) url += `&search=${filters.customerSearch}`;
            if (filters.assignment === 'assigned') url += '&assigned=true';
            if (filters.assignment === 'unassigned') url += '&assigned=false';

            const response = await fetch(url);

            const data = await response.json();

            setTickets(data.data);

            localStorage.setItem('cachedTickets', JSON.stringify(data.data));

            setTotalPages(data.last_page);
            setCurrentPage(data.current_page);

        } catch (error) {
            console.error('Unable to grab ticket:', error);
            setTickets([]);
            setViewDescription('oft an error, check the console');
        }
    };

    const getFilterDescription = () => {
        const hasActiveFilter = Object.values(filters).some(value => value !== null);
        
        return hasActiveFilter ? 'Showing Filtered Tickets' : 'Showing All Tickets';
    };
    
    useEffect(() => {
        setViewDescription(getFilterDescription());
    }, [filters]);

    const formatPriority = (priority) => {
        return priority.toUpperCase();
    };
    
    const formatType = (type) => {
        return type.split('_')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    };

    return (

        <div className="list">

            <div className="top">
                <div>
                    <h2>Ticket Queue</h2>
                    <h3>{viewDescription}</h3>
                </div>
                <div className="pagination">
                    <div className="paginationButtons">
                        <button 
                                onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
                                disabled={currentPage === 1}
                            >
                                Previous
                        </button>
                        <button 
                                onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
                                disabled={currentPage === totalPages}
                            >
                                Next
                        </button>
                    </div>
                    <span> Showing {currentPage} of {totalPages}</span>
                </div>
            </div>

            <div className="tickets">
                {tickets.map(ticket=> (
                    <div key={ticket.id} className="single">
                        <div className="content">
                            <h3>{ticket.subject}</h3>
                            <p>{ticket.content.substring(0,100)}...</p>
                            <span>{ticket.user.id} - {ticket.user.name}</span>
                        </div>

                        <div className="meta">
                            <span>Ticket ID - {ticket.id}</span>
                            <span>{ticket.created_at}</span>
                            <span>{ticket.assigned_to ? 'Assigned' : 'Unassigned'}</span>
                            <span>{formatPriority(ticket.priority)}</span>
                            <span>{formatType(ticket.type)}</span>
                            <button 
                                    className="actionButton"
                                    onClick={() => onTicketSelect(ticket)}
                                >
                                    Actions
                            </button>
                        </div>
                    </div>
                ))};
            </div>
        </div>
    )


}

export default TicketList;