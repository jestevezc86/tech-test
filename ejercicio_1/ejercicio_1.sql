SELECT DISTINCT a.name AS student, p.name AS province FROM students a JOIN provinces p ON a.id_province = p.id WHERE a.id NOT IN (SELECT id_alumno FROM exams WHERE nota >= 5)
