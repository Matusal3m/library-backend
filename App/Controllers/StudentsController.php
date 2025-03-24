<?php
namespace App\Controllers;

use App\Models\Services\StudentsService;
use App\Validation\IdValidator;
use Exception;
use Http\Request;
use Http\Response;

class StudentsController
{
    public function __construct(
        private StudentsService $studentsService
    ) {}

    public function create(Request $request, Response $response): void
    {
        $data = $request->body();

        try {
            $student = $this->studentsService->createStudent($data);

            $response->json([
                'id'                => $student->getId(),
                'name'              => $student->getName(),
                'enrollment_number' => $student->getEnrollmentNumber(),
                'has_active_loan'   => $student->getHasActiveLoan(),
            ]);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(Request $request, Response $response, array $id): void
    {
        $data = $request->body();
        try {
            $studentId = IdValidator::validateOne($id[0]);

            $this->studentsService->updateStudent($studentId, $data);

            $response->json(['message' => 'Student updated!'], 201);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function getById(Request $request, Response $response, array $id): void
    {
        try {
            $studentId = IdValidator::validateOne($id[0]);

            $student = $this->studentsService->getStudent($studentId);

            $response->json([
                'id'                => $student->getId(),
                'name'              => $student->getName(),
                'enrollment_number' => $student->getEnrollmentNumber(),
                'has_active_loan'   => $student->getHasActiveLoan(),
            ]);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function getAll(Request $request, Response $response): void
    {
        try {
            $students = $this->studentsService->getAllStudents();
            $response->json($students);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function delete(Request $request, Response $response, array $id): void
    {
        try {
            $studentId = IdValidator::validateOne($id[0]);
            $this->studentsService->deleteStudent($studentId);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
