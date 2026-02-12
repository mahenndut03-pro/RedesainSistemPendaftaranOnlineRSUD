// Simple client-side validator for No BPJS (13 digits)
export function isValidNoBpjs(noBpjs) {
    if (noBpjs === null || typeof noBpjs === 'undefined') return false;
    return /^\d{13}$/.test(String(noBpjs).trim());
}

export default isValidNoBpjs;
