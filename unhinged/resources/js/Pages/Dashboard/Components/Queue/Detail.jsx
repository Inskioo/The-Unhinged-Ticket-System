import React, { useState, useEffect } from 'react';

const TicketDetail = ({ ticket, supportAgents, onClose, onUpdate }) => {
    const [isAssigning, setIsAssigning] = useState(false);
    const [selectedAgent, setSelectedAgent] = useState('');
    const [message, setMessage] = useState('');
    const [currentType, setCurrentType] = useState(ticket.type);
    const [userTicketCounts, setUserTicketCounts] = useState({
        resolved: 0,
        unresolved: 0
    });

    useEffect(() => {
        setCurrentType(ticket.type);
    }, [ticket.type]);

    const showMessage = (msg) => {
        setMessage(msg);
        setTimeout(() => setMessage(''), 3000);
    };

    const handleAssign = async () => {
        if (!selectedAgent) return;
    
        try {
            setIsAssigning(true);
            const response = await fetch(`/api/tickets/${ticket.id}/assign`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ agent_id: selectedAgent })
            });
            
            if (!response.ok) throw new Error('Failed to assign ticket');
            const data = await response.json();
            console.log(data.message);
            showMessage(data.message);

            
        } catch (error) {
            console.error('Error assigning ticket:', error);
        } finally {
            setIsAssigning(false);
        }
    };

    const handleResolve = async () => {
        try {
            const response = await fetch(`/api/tickets/${ticket.id}/resolve`, {
                method: 'POST'
            });
            
            if (!response.ok) throw new Error('Cannot resolve Ticket.');
            
            onClose();
        } catch (error) {
            console.error('We cannot close this one boys:', error);
        }
    };

    const handleType = async (type) => {
        if (type === currentType) return;
        
        try {
            const response = await fetch(`/api/tickets/${ticket.id}/type`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ type: type })
            });
            
            if (!response.ok) throw new Error('Type system is unhinged. hng');
            
            const data = await response.json();
            setCurrentType(type);
            showMessage(data.message);
            onUpdate();
            
        } catch (error) {
            console.error('cannot set type :( :', error);
        }
    };

    useEffect(() => {
        const fetchUserTicketCounts = async () => {
            try {
                const response = await fetch(`/api/tickets/user/${ticket.user.id}/counts`);
                const data = await response.json();
                setUserTicketCounts(data);
            } catch (error) {
                console.error('what even are numbers:', error);
            }
        };

        fetchUserTicketCounts();
    }, [ticket.user.id]);

    return (
        <div className="detail">
            {message && <div className="message">{message}</div>}
            <div className="meta">
                <span>ID - {ticket.id}</span>
                <span>{ticket.created_at}</span>
                <span>Priority: {ticket.priority}</span>
                <span>
                    {ticket.assigned_to ? 
                        `Assigned to: ${ticket.assigned_to.name}` : 
                        'Unassigned'
                    }
                </span>
            </div>
            <div className="head">
                
                <h2>{ticket.id} - {ticket.user.name}</h2>
                <div className="counts">
                    <span>Unresolved: <b>{userTicketCounts.unresolved}</b></span>
                    <span>resolved: <b>{userTicketCounts.resolved}</b></span>
                </div>
            </div>
            <div className="content">
                <h3>{ticket.subject}</h3>
                <p>{ticket.content}</p>
            </div>
            <div className="assign">
                <select 
                    value={selectedAgent}
                    onChange={(e) => setSelectedAgent(e.target.value)}
                    disabled={isAssigning}
                >
                    <option value="">Select Agent</option>
                    {supportAgents.map(agent => (
                        <option key={agent.id} value={agent.id}>
                            {agent.name}
                        </option>
                    ))}
                </select>
                <button 
                    onClick={handleAssign}
                    disabled={!selectedAgent || isAssigning}
                >
                    Assign
                </button>
            </div>
            <div className="actions">
                {ticket.status !== 'resolved' && (
                    <button onClick={handleResolve}>
                        Mark as Resolved
                    </button>
                )}
                <button 
                    onClick={() => handleType('slightly_unhinged')}
                    disabled={currentType === 'slightly_unhinged'}
                    className={currentType === 'slightly_unhinged' ? 'active' : ''}
                >
                    Mark as Slightly Unhinged
                </button>
                <button 
                    onClick={() => handleType('wildly_unhinged')}
                    disabled={currentType === 'wildly_unhinged'}
                    className={currentType === 'wildly_unhinged' ? 'active' : ''}
                >
                    Mark as Wildly Unhinged
                </button>
            </div>
        </div>
    );
};

export default TicketDetail;