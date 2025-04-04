-- *** SET THIS VARIABLE FOR EACH DUMP YOU MIGRATE ***
SET @source_lab = 'CLC'; -- Or 'LAB_1', 'LAB_3', 'SERVER_B', etc.

-- Run this for EACH lab's data dump
INSERT INTO ecat.test_attempts (
    -- OMIT attempt_id - let ecat's AUTO_INCREMENT generate a unique one
    student_id,
    start_time,
    end_time,
    status,
    total_score,
    created_at,
    original_session_id, -- Store the original ID here
    source_lab_id        -- Store the lab identifier here
)
SELECT
    ps.user_id,
    ps.start_time,
    ps.end_time,
    CASE
        WHEN ps.start_time IS NOT NULL AND ps.end_time IS NOT NULL THEN 'Completed'
        WHEN ps.start_time IS NOT NULL AND ps.end_time IS NULL THEN 'In Progress'
        ELSE 'Not Started'
    END AS status,
    COALESCE(score_calc.calculated_score, 0) AS total_score,
    ps.created_at,
    ps.session_id,      -- <<< Select the original session_id
    @source_lab         -- <<< Select the source lab identifier
FROM
    entranceexam.player_sessions ps
LEFT JOIN (
    SELECT
        session_id,
        SUM(is_correct) AS calculated_score
    FROM
        entranceexam.answer_attempts
    GROUP BY
        session_id
) AS score_calc ON ps.session_id = score_calc.session_id;