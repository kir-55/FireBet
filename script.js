function toggleGroupMembers(groupId) {
    const groupMembersDiv = document.getElementById('group-members-' + groupId);
    const toggleSymbol = document.getElementById('toggle-symbol-' + groupId);
    if (groupMembersDiv.style.display === 'none' || groupMembersDiv.style.display === '') {
        groupMembersDiv.style.display = 'block';
        toggleSymbol.textContent = '▼';
    } else {
        groupMembersDiv.style.display = 'none';
        toggleSymbol.textContent = '►';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const newCoefficientSpan = document.getElementById('new-coefficient');
    const currentCoefficientSpan = document.getElementById('current-coefficient');
    const betForm = document.getElementById('bet-form');

    if (amountInput) {
        amountInput.addEventListener('input', function() {
            const amount = parseFloat(amountInput.value);
            const currentCoefficient = parseFloat(currentCoefficientSpan.textContent.replace('x', ''));
            if (!isNaN(amount) && amount > 0) {
                const newTotalBets = totalBets + amount;
                const newGroupBets = groupBets + amount;
                const newProbability = newGroupBets / newTotalBets;
                const newCoefficient = newProbability > 0 ? (1 / newProbability).toFixed(2) + 'x' : '0x';
                newCoefficientSpan.textContent = newCoefficient;
            } else {
                newCoefficientSpan.textContent = '-';
            }
        });
    }

    if (betForm) {
        betForm.addEventListener('submit', function(event) {
            const confirmBet = confirm("Are you sure you want to place this bet?");
            if (!confirmBet) {
                event.preventDefault();
            }
        });
    }

    const showMoreBtn = document.getElementById('show-more-comments');
    const hideBtn = document.getElementById('hide-comments');
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', showAllComments);
    }
    if (hideBtn) {
        hideBtn.addEventListener('click', hideAllComments);
    }
});

function likeComment(commentId) {
    fetch('like_comment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ commentId: commentId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeBtn = document.querySelector(`button[onclick='likeComment(${commentId})']`);
            likeBtn.textContent = `❤️ ${data.likes}`;
        } else {
            alert('Failed to like the comment.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function showAllComments() {
    const allComments = document.getElementById('all-comments');
    const showMoreBtn = document.getElementById('show-more-comments');
    const hideBtn = document.getElementById('hide-comments');
    allComments.style.display = 'block';
    showMoreBtn.style.display = 'none';
    hideBtn.style.display = 'inline';
}

function hideAllComments() {
    const allComments = document.getElementById('all-comments');
    const showMoreBtn = document.getElementById('show-more-comments');
    const hideBtn = document.getElementById('hide-comments');
    allComments.style.display = 'none';
    showMoreBtn.style.display = 'inline';
    hideBtn.style.display = 'none';
}