//file name - script.js
document.addEventListener("DOMContentLoaded", () => {
    const signInForm = document.getElementById('signIn');
    const signUpForm = document.getElementById('signup');
    const signInButton = document.getElementById('signInButton');
    const signUpButton = document.getElementById('signUpButton');

    signInButton.addEventListener('click', () => {
        signUpForm.style.display = 'none';
        signInForm.style.display = 'block';
    });

    signUpButton.addEventListener('click', () => {
        signInForm.style.display = 'none';
        signUpForm.style.display = 'block';
    });

    const sendOtpButton = document.getElementById('sendOtp');
    const verifyOtpButton = document.getElementById('verifyOtp');
    const otpField = document.getElementById('otp');
    const signupButton = document.getElementById('signupButton');
    let isOtpVerified = false;

    sendOtpButton.addEventListener('click', () => {
        const email = document.getElementById('email').value;
        if (!email) {
            alert('Please enter an email address to send OTP.');
            return;
        }
        fetch('send_otp.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email }),
            credentials: 'include'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('OTP sent to your email.');
                } else {
                    alert('Failed to send OTP. Please try again.');
                }
            });
    });

    verifyOtpButton.addEventListener('click', () => {
        const otp = otpField.value;
        if (!otp) {
            alert('Please enter the OTP.');
            return;
        }
        fetch('verify_otp.php', {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ otp: otp })
            
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    isOtpVerified = true;
                    alert('OTP verified successfully!');
                    signupButton.disabled = false;
                } else {
                    alert('Invalid OTP. Please try again.');
                }
            });
    });
});
